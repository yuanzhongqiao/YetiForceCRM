<?php
/**
 * YetiForce shop InstallInCloud file.
 *
 * @package   App
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace App\YetiForce\Shop\Product;

/**
 * YetiForce shop InstallInCloud class.
 */
class YetiForceInstallInCloud extends \App\YetiForce\Shop\AbstractBaseProduct
{
	/** {@inheritdoc} */
	public $label = 'YetiForce Cloud';
	/** {@inheritdoc} */
	public $category = 'CloudHosting';
	/** {@inheritdoc} */
	public $pricesType = 'selection';
	/** {@inheritdoc} */
	public $website = 'https://yetiforce.com/en/marketplace/cloud';
	/** {@inheritdoc} */
	public $prices = [
		'Micro' => 40,
		'Small' => 100,
		'Medium' => 200,
		'Large' => 400,
		'Corporation' => 2000,
	];

	/** {@inheritdoc} */
	public $customFields = [
		'subdomain' => [
			'label' => 'LBL_SHOP_DOMAIN_PREFIX',
			'type' => 'text',
			'append' => '.yetiforce.eu',
			'validator' => 'required,custom[onlyLetterNumber],minSize[3],maxSize[20]'
		],
		'email' => [
			'label' => 'LBL_EMAIL',
			'type' => 'email',
			'info' => 'LBL_EMAIL_INFO',
			'validator' => 'required,funcCall[Vtiger_Email_Validator_Js.invokeValidation]'
		]
	];

	/** {@inheritdoc} */
	public $companyDataForm = false;

	/** {@inheritdoc} */
	public $featured = true;

	/** {@inheritdoc} */
	public function verify($cache = true): bool
	{
		return true;
	}
}
