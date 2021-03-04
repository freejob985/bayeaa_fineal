<?php


namespace App\Http\Controllers\Post\CreateOrEdit\Traits;

use App\Models\Package;
use Illuminate\Support\Facades\Cache;

trait PricingTrait
{
	/**
	 * Check & Get the selected Package
	 *
	 * @return mixed|null
	 */
	public function getSelectedPackage()
	{
		$package = null;
		
		// Make this available only on Post Creation pages
		if (
			(config('settings.single.publication_form_type') == '1' && getSegment(2) == 'create')
			|| (config('settings.single.publication_form_type') == '2' && getSegment(1) == 'create')
		) {
			if (request()->filled('package')) {
				$packageId = request()->get('package');
				$cacheId = 'package.tid.' . $packageId . '.' . config('app.locale');
				$package = Cache::remember($cacheId, $this->cacheExpiration, function () use ($packageId) {
					$package = Package::trans()
						->with(['currency'])
						->where('translation_of', $packageId)
						->first();
					
					return $package;
				});
			}
		}
		
		return $package;
	}
	
	/**
	 * Check if the Package selection is required and Get the Pricing Page URL
	 *
	 * @param $package
	 * @return string|null
	 */
	public function getPricingPage($package)
	{
		$pricingUrl = null;
		
		// Check if the 'Pricing Page' must be started first, and make redirection to it.
		if (config('settings.single.pricing_page_enabled') == '1') {
			if (empty($package)) {
				$pricingUrl = lurl(trans('routes.pricing')) . '?from=' . request()->path();
			}
		}
		
		return $pricingUrl;
	}
	
	/**
	 * Share the Package's Info
	 *
	 * @param $package
	 */
	public function sharePackageInfo($package)
	{
		$currentPackageId = 0;
		$currentPackagePrice = 0;
		
		// Set the Package's pictures limit
		if (!empty($package)) {
			$currentPackageId = $package->tid;
			$currentPackagePrice = $package->price;
			
			if (
				isset($package->pictures_limit)
				&& !empty($package->pictures_limit)
				&& $package->pictures_limit > 0
			) {
				config()->set('settings.single.pictures_limit', $package->pictures_limit);
				view()->share('picturesLimit', $package->pictures_limit);
			}
		}
		
		view()->share('currentPackageId', $currentPackageId);
		view()->share('currentPackagePrice', $currentPackagePrice);
	}
}
