<?php


namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\CountryRequest as StoreRequest;
use App\Http\Requests\Admin\CountryRequest as UpdateRequest;

class CountryController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Country');
		$this->xPanel->setRoute(admin_uri('countries'));
		$this->xPanel->setEntityNameStrings(trans('admin.country'), trans('admin.countries'));
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		$this->xPanel->addButtonFromModelFunction('line', 'cities', 'citiesBtn', 'beginning');
		$this->xPanel->addButtonFromModelFunction('line', 'admin_divisions1', 'adminDivisions1Btn', 'beginning');
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'      => 'id',
			'label'     => '',
			'type'      => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
			'name'  => 'code',
			'label' => trans('admin.Code'),
		]);
		$this->xPanel->addColumn([
			'name'  => 'name',
			'label' => trans('admin.Local Name'),
		]);
		$this->xPanel->addColumn([
			'name'          => 'asciiname',
			'label'         => trans('admin.Name'),
			'type'          => 'model_function',
			'function_name' => 'getNameHtml',
		]);
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans('admin.Active'),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'              => 'code',
			'label'             => trans('admin.Code'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Enter the country code'),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		], 'create');
		$this->xPanel->addField([
			'name'              => 'name',
			'label'             => trans('admin.Local Name'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Local Name'),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'asciiname',
			'label'             => trans('admin.Name'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Enter the country name'),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'capital',
			'label'             => trans('admin.Capital') . ' (' . trans('admin.Optional') . ')',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Capital'),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'continent_code',
			'label'             => trans('admin.Continent'),
			'type'              => 'select2',
			'attribute'         => 'name',
			'model'             => 'App\Models\Continent',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'tld',
			'label'             => trans('admin.TLD') . ' (' . trans('admin.Optional') . ')',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Enter the country tld'),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'phone',
			'label'             => trans('admin.Calling code') . ' (' . trans('admin.Optional') . ')',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.Enter the country calling code'),
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'currency_code',
			'label'             => trans('admin.Currency Code'),
			'type'              => 'select2',
			'attribute'         => 'code',
			'model'             => 'App\Models\Currency',
			'hint'              => trans('admin.Default country currency'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		// Check the Currency Exchange plugin data
		if (config('plugins.currencyexchange.installed')) {
			$this->xPanel->addField([
				'name'              => 'currencies',
				'label'             => trans("currencyexchange::messages.Currencies") . ' (' . trans('currencyexchange::messages.Optional') . ')',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => trans('currencyexchange::messages.eg_currencies_field'),
				],
				'hint'              => trans('currencyexchange::messages.currencies_codes_list_menu_per_country_hint', [
					'url' => admin_url('currencies')
				]),
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
		}
		$this->xPanel->addField([
			'name'   => 'background_image',
			'label'  => trans('admin.Background Image'),
			'type'   => 'image',
			'upload' => true,
			'disk'   => 'public',
			'hint'   => trans('admin.Choose a picture from your computer') . '<br>' . trans('admin.country_background_image_info'),
		]);
		$this->xPanel->addField([
			'name'              => 'languages',
			'label'             => trans('admin.Languages'),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans('admin.eg_languages_field'),
			],
			'hint'              => trans('admin.languages_codes_list_hint', ['url' => admin_url('languages')]),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'admin_type',
			'label'             => trans('admin.admin_division_type'),
			'type'              => 'enum',
			'hint'              => trans('admin.eg_admin_division_type'),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'admin_field_active',
			'label'             => trans('admin.active_admin_division_field'),
			'type'              => 'checkbox',
			'hint'              => trans('admin.active_admin_division_field_hint', [
				'admin_type_hint' => trans('admin.admin_division_type'),
			]),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				//'style' => 'margin-top: 20px;',
			],
		]);
		/*
		$this->xPanel->addField([
			'name'  => 'active',
			'label' => trans('admin.Active'),
			'type'  => 'checkbox',
		]);
		*/
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
