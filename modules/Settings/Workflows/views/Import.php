<?php

/**
 * Import View Class for Workflows Settings
 * @package YetiForce.View
 * @license licenses/License.html
 * @author Maciej Stencel <m.stencel@yetiforce.com>
 */
class Settings_Workflows_Import_View extends Settings_Vtiger_Index_View
{

	public function process(Vtiger_Request $request)
	{
		$log = vglobal('log');
		$log->debug('Start ' . __CLASS__ . ':' . __FUNCTION__);
		$qualifiedModule = $request->getModule(false);
		$viewer = $this->getViewer($request);

		if ($request->has('upload') && $request->get('upload') == 'true') {
			$xmlName = $_FILES['imported_xml']['name'];
			$uploadedXml = $_FILES['imported_xml']['tmp_name'];
			$xmlError = $_FILES['imported_xml']['error'];
			$extension = end(explode('.', $xmlName));

			if ($xmlError == UPLOAD_ERR_OK && $extension === 'xml') {
				$xml = simplexml_load_file($uploadedXml);

				$params = [];
				$taskIndex = 0;
				foreach ($xml as $fieldsKey => $fieldsValue) {
					foreach ($fieldsValue as $fieldKey => $fieldValue) {
						foreach ($fieldValue as $columnKey => $columnValue) {
							if ($columnKey === 'conditions') {
								$columnKey = 'test';
							} else if ($columnKey == 'type' && empty($columnValue)) {
								$columnValue = 'basic';
							}
							switch ($fieldKey) {
								case 'workflow_task':
									$params[$fieldsKey][$taskIndex][$columnKey] = (string) $columnValue;
									break;

								default:
									$params[$fieldsKey][$columnKey] = (string) $columnValue;
							}
						}
						if ($fieldKey === 'workflow_task') {
							$taskIndex++;
						}
					}
				}
				$workflowModel = Settings_Workflows_Module_Model::getInstance('Settings:Workflows');
				$recordId = $workflowModel->importWorkflow($params);

				$viewer->assign('RECORDID', $recordId);
				$viewer->assign('UPLOAD', true);
			} else {
				$viewer->assign('UPLOAD_ERROR', vtranslate('LBL_UPLOAD_ERROR', $qualifiedModule));
				$viewer->assign('UPLOAD', false);
			}
		}

		$viewer->assign('QUALIFIED_MODULE', $qualifiedModule);
		$viewer->view('Import.tpl', $qualifiedModule);
		$log->debug('End ' . __CLASS__ . ':' . __FUNCTION__);
	}

	function getHeaderCss(Vtiger_Request $request)
	{
		$headerCssInstances = parent::getHeaderCss($request);
		$moduleName = $request->getModule();
		$cssFileNames = [
			"modules.Settings.$moduleName.Import",
		];
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		return array_merge($cssInstances, $headerCssInstances);
	}
}
