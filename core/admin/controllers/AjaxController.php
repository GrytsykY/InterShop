<?php


namespace core\admin\controllers;


use core\base\controllers\BaseAjax;

class AjaxController extends BaseAjax
{

	public function ajax(){
		$aw=$_GET;
		if (isset($this->data['ajax'])) {

			switch ($this->data['ajax']){

				case 'sitemap':

					return (new CreatesitemapController())->inputData($this->data['links_counter'],false);

					break;
			}
		}

		return json_encode(['success' => '0', 'message' => 'No ajax variable']);
	}

}