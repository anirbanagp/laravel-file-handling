<?php

namespace App\Http\Library\Crud\Traits;

/**
 * this will contain some extra functionality that will help to add rbac on crud
 *
 * @author Anirban Saha
 */
trait RbacHelper
{
    public $base_id;

    protected function setDefaultActions()
 	{
 		$default = [
 			'view' => [
 						'label' => 'view',
 						'class' => 'btn btn-info',
 						'icon' 	=> 'info',
 						'link' 	=> route($this->route_slug.'view',$this->base_id)
 					],
 			'edit' => [
 						'label' =>  'edit',
 						'class' => 'btn btn-warning',
 						'icon' 	=> 'create',
 						'link' 	=> route($this->route_slug.'edit',$this->base_id)
 					],
 			'delete' => [
 						'label' =>  'delete',
 						'class' => 'delete_button btn btn-danger',
 						'icon' 	=> 'delete_sweep',
 						'link' 	=> route($this->route_slug.'delete',$this->base_id)
 					],
 		];
 		//set default action button
		$action_type	=	['canView', 'canModify', 'canModify'];
		$i = 0;
		foreach ($default as $key => $each) {
			if(!in_array($key,$this->unset_actions_button) && (!$this->use_rbac || ($this->use_rbac && $this->{$action_type[$i]}($this->module_slug) ))){
				$this->setActionButton($each['label'],$each['class'],$each['icon'],$each['link']);
			}
			$i++;
		}
 	}
}
