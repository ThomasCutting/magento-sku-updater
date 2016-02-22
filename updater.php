<?php
/**
 * SKU Updater
 * @package SkuUpdater
 */

/*
	Require autoload for the BatchCSVProcessor
*/
require_once 'autoload.php';

/*
	NEWLINE - \n
	_TAB_ - \t
*/
define('NEWLINE',"\n");
define('_TAB_',"\t");

/**
 * Class SkuUpdater
 * --
 * Processes the file stream from BatchFileReader into multi-dimensional, combined arrays.
 * @package SkuUpdater
 */
class SkuUpdater
{
	/**
	 * @var $resourceArray
	 * Handles where we store all of our resource(s) that are parsed through the batch csv processor.
	 */
	private $resourceArray;

	/**
	 * @var $resourceOptions[]
	 * Handles all the options for the CSV mappings from the assoc - array.
	 */
	private $resourceOptions;

	/**
	 * __construct
	 * --
	 * @param $file_name
	 */
	public function __construct($file_name,$file_options,$mage_app_directory) {
		$this->resourceArray = BatchCSVProcessor::processResourceToArray(fopen($file_name,'rb'));
		$this->resourceOptions = $file_options;
		
		// process the request to include mage core ( bootstrap & mage . php )
        if ($mage_app_directory && is_dir($mage_app_directory)) {
            if (file_exists($mage_app_directory . '/bootstrap.php')) {
                // include the bootstrapped file for Magento ( this acts as a pre-processor )
                include $mage_app_directory . '/bootstrap.php';
            }
            if (file_exists($mage_app_directory . '/mage.php')) {
                // include the mage.php file
                include $mage_app_directory . '/mage.php';
            }
        }
	}

	/**
	 * updateSKUs
	 * --
	 * Updates the SKUs of the internal database.
	 */
	public function updateSKUs() {
		$array_index = 0;
		$options = $this->resourceOptions;

		foreach ($this->resourceArray as $row) {
			if( $row[$options['status_head']] === $options['status_head_choice_update'] ) {
				// this is the old sku ( we fetch the "head" or "column name" from our internal options )
				$old_sku = $row[$options['old_sku_head']];
				// the new sku ( again, we fetch the "head" or "column name" from our internal options )
				$new_sku = $row[$options['new_sku_head']];
				
				if($options['verbose']) {
					echo "Old SKU: " . $old_sku . " \t New SKU: " . $new_sku . NEWLINE;
				}

				try {
					// attempt to update the sku ( save() method is the one that will throw, if at all. )
					$this->updateSKU($old_sku,$new_sku);
				} catch(Exception $e) {
					error_log($e->getTraceAsString());
				}
			} else if( $row[$options['status_head']] === $options['status_head_choice_delete'] ) {
				// delete the product, by ( old sku )
				$this->deleteProductBySelector('sku',$row[$options['old_sku_head']]);
			}

			// increment the index.
			$array_index++;
		}

	}

	/**
	 * updateUPC
	 * --
	 * Handles updating UPC(s) for any catalog product.
	 * @param $old_upc
	 * @param $new_upc
	 */
	private function updateUPC($old_upc,$new_upc) {
		// get product model, update the upc, and save.
		Mage::getModel('catalog/product')
		->loadByAttribte('upc',$old_upc)
		->setUPC($new_upc)
		->save();
	}

	/**
	 * updateSKU
	 * --
	 * Handles updating sku(s) for any catalog product.
	 * @param $old_sku
	 * @param $new_sku
	 */
	private function updateSKU($old_sku,$new_sku) {
		// get product model, update sku, and save.
		$updated_sku_model = Mage::getModel('catalog/product')
			->loadByAttribte('sku',$old_sku)
			->setSKU($new_sku)
			->save();
	}

	/**
	 * deleteProductBySelector
	 * --
	 * @param $selector_attribute
	 * @param $selector_id
	 */
	private function deleteProductBySelector($selector_attribute,$selector_id) {
		// make sure to format the selector, correctly.
		if(!is_string($selector_attribute)) {
			$selector_attribute = (string)$selector_attribute;
		}

		// get the product by (provided) selector.
		$product = Mage::getModel('catalog/product')->loadByAttribte($selector_attribute,$selector_id);

		// try to delete the product
		try {
			$product->delete();
		} catch(Exception $e) {
			error_log($e->getTraceAsString());
		}

		// be sure to fulfill parents objective.
		return true;
	}

	/**
	 * deleteProductsBySelector
	 * --
	 * @param $selector_attribute
	 * @param $selector_id[]
	 */
	private function deleteProductsBySelector($selector_attribute,$selector_ids) {
		// make sure to format the selector, correctly.
		if(!is_string($selector_attribute)) {
			$selector_attribute = (string)$selector_attribute;
		}

		foreach ($selector_ids as $selector_id) {
			// get the product by (provided) selector.
			$product = Mage::getModel('catalog/product')->loadByAttribte($selector_attribute,$selector_id);

			// try to delete the product
			try {
				$product->delete();
			} catch(Exception $e) {
				error_log($e->getTraceAsString());
			}
		}

		// be sure to fulfill parents objective.
		return true;
	}	

}