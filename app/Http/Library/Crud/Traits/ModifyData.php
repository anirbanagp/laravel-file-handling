<?php
namespace App\Http\Library\Crud\Traits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * This will contain all methods we need to save data into database
 *
 *  @author Anirban Saha
 */
trait ModifyData
{
	/**
	 * model directory path
	 * @var string
	 */
	public $model_path = 'App\Models\\';

	/**
	 * current model instance
	 * @var object
	 */
	public $current_model;

	/**
	 * this will generate all data for insert/update
	 * @param  object  $request form request object
	 * @param  boolean $update  id it update ?
	 * @return array           array of data to be updated/inserted
	 */
	private function getPostData($request,$update=false)
	{
		$this->model_name = $this->model_name ? $this->model_name : $this->model_path.studly_case(str_singular($this->table_name));
		$this->setInsertForm(false);
		$final_fields = $this->form_input_list;
		$row_data = [];
		foreach ($this->extra_fields as $key => $value) {
			if($value["callback_function"] != null) {
				$this->{$value["callback_function"]}($request,$request->$key,$this->action_type);
			}
		}
		foreach ($final_fields as $field_name => $details) {
			if(in_array($field_name,array_keys($this->callback_coloumn))){
				$call_back_details = $this->callback_coloumn[$field_name];
				$row_data[$field_name] =  $this->{$call_back_details['callback_function']}($request,$request->$field_name,$this->action_type);
			}
			if($details['field_type'] == 'file'){
				if($request->hasFile($field_name)){
					if($update){
						$old = $this->model_name::find($request->id);
						Storage::delete($old->$field_name);
					}
					if($path = $this->uploadFile($request->file($field_name))){
						$row_data[$field_name] = $path;
					}else {
						return false;
					}
				} else {
					unset($row_data[$field_name]);
				}
			}elseif (in_array($details['field_type'],['radio','select','multiselect'])){
				if(in_array($request->$field_name,array_keys($details['option_values']))){
					$row_data[$field_name] = $request->$field_name;
				}else{
					return false;
				}
			} elseif($details['field_type'] == 'password') {
				if(!isset($request->$field_name)) {
					unset($row_data[$field_name]);
				}else {
					$row_data[$field_name] = $request->$field_name;
				}
			}else {
				$row_data[$field_name] = $request->$field_name;
			}
			// if(!isset($request->$field_name) && in_array($field_name,array_keys($this->callback_coloumn)) == false){
			// 	unset($row_data[$field_name]);
			// }
		}
		return $row_data;
	}
	/**
	 * this will upload a file into respective path
	 * @param  object $file file to be uploaded
	 * @param  string $path where to upload, nullable
	 * @return mixed       filepath on success, false on fail
	 */
	public function uploadFile($file,$path=null)
	{
		$path = is_null($path) ? $this->upload_path : $path;
		if($filename = $file->store($path)){
			return $filename;
		}else{
			return false;
		}
	}
	/**
	 * this will insert data into database
	 * @param  object $request form request object
	 * @return bool          true on success
	 */
	private function insertDB($request)
	{
		$row_data = $this->getPostData($request);
		$this->current_model = $this->model_name::create($row_data);
		return true;
	}
	/**
	 * this will update a row
	 * @param  object $request form request object
	 * @return bool          true on success
	 */
	private function updateDB($request)
	{
		$row_data = $this->getPostData($request,true);
		$this->model_name::find($request->id)->update($row_data);
		$this->current_model = $this->model_name::find($request->id);
		return true;
	}
	private function deleteDB($id){
		$this->model_name = $this->model_name ? $this->model_name : $this->model_path.studly_case(str_singular($this->table_name));
		$file_field = $this->getFileTypeField();
		$old = $this->model_name::find($id);
		if(count($file_field))
		foreach ($file_field as $field_name) {
			Storage::delete($old->$field_name);
		}
		$old->delete();
		return true;
	}

}
