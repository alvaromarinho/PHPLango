<?php 

require_once 'php-activerecord/ActiveRecord.php';

class Maker
{
	private $html_controller;
	private $html_model;
	private $html_view;
	private $html_sidebar;
	private $html_index;
	private $html_create;

	public function getHtmlController()
	{
		return $this->html_controller;
	}

	public function setHtmlController($config)
	{
		$relationship = "";
		if(strlen(implode("", $config['relationship'])) > 0){
			$return = "['".$config['table']."' => \$".$config['table'].", ";
			foreach ($config['relationship'] as $table => $column) {
				$relationship .= "\t\t\$".$table." = ".ActiveRecord\classify($table, true)."::find('all', ['select' => '".$table.".id, ".$table.".".$column."']);\n";
				$return .= "'".$table."' => \$".$table.", ";
			}
			$return = substr($return, 0, -2)."]";
		}
		else 
			$return = "['".$config['table']."' => \$".$config['table']."]";
		$html  = "";
		$html .= "<?php \n\nclass ".$config['name']."\n{\n\t";
		$html .= "public static function index(\$array)\n\t{\n\t\t\$".$config['table']." = ".$config['model']."::find('all');\n\t\treturn array('data' => ['".$config['table']."' => \$".$config['table']."]);\n\t}\n\n\t";
		$html .= "public static function create()\n\t{\n\t\t\$".$config['table']." = new ".$config['model']."();\n\t\tif (!empty(\$_POST)) {\n\t\t\t\$".$config['table']."->set_attributes(\$_POST);\n\t\t\tif(\$".$config['table']."->is_valid()){\n\t\t\t\ttry {\n\t\t\t\t\t\$".$config['table']."->save();\n\t\t\t\t\t\$message = 'Saved successfully!';\n\t\t\t\t\t\$class   = 'success';\n\t\t\t\t} catch (Exception \$e) {\n\t\t\t\t\t\$message = \$e->getMessage();\n\t\t\t\t\t\$class   = 'danger';\n\t\t\t\t}\n\t\t\t} else {\n\t\t\t\t\$message = implode('<br>', \$".$config['table']."->errors->full_messages());\n\t\t\t\t\$class   = 'danger';\n\t\t\t}\n\t\t\treturn array('redirect' => ROOT.'".$config['table']."', 'message' => \$message, 'class' => \$class);\n\t\t}\n".$relationship."\t\treturn array('data' => ".$return.");\n\t}\n\n\t";
		$html .= "public static function edit(\$id)\n\t{\n\t\t\$".$config['table']." = ".$config['model']."::find(\$id);\n\t\tif (!empty(\$_POST)) {\n\t\t\ttry {\n\t\t\t\t\$".$config['table']."->update_attributes(\$_POST);\n\t\t\t\tif(\$".$config['table']."->is_valid()){\n\t\t\t\t\t\$message = 'Updated successfully!';\n\t\t\t\t\t\$class   = 'success';\n\t\t\t\t} else {\n\t\t\t\t\t\$message = implode('<br>',\$".$config['table']."->errors->full_messages());\n\t\t\t\t\t\$class   = 'danger';\n\t\t\t\t}\n\t\t\t} catch (Exception \$e) {\n\t\t\t\t\$message = \$e->getMessage();\n\t\t\t\t\$class   = 'danger';\n\t\t\t}\n\t\t\treturn array('redirect' => ROOT.'".$config['table']."', 'message' => \$message, 'class' => \$class);\n\t\t}\n".$relationship."\t\treturn array('data' => ".$return.", 'view' => 'create');\n\t}\n\n\t";
		$html .= "public static function delete(\$id)\n\t{\n\t\ttry {\n\t\t\t\$".$config['table']." = ".$config['model']."::find(\$id)->delete();\n\t\t\t\$message = 'Deleted successfully!';\n\t\t\t\$class   = 'success';\n\t\t} catch (Exception \$e) {\n\t\t\t\$message = \$e->getMessage();\n\t\t\t\$class   = 'danger';\n\t\t}\n\t\treturn array('redirect' => ROOT.'".$config['table']."', 'message' => \$message, 'class' => \$class);\n\t}\n";
		$html .= "}";
		$this->html_controller = $html;
	}

	public function getHtmlModel()
	{
		return $this->html_model;
	}

	public function setHtmlModel($config)
	{
		$html = "<?php \n\nclass ".$config['name']." extends ActiveRecord\Model\n{\n\t";
		foreach ($config['relationship'] as $type => $tables) {
			$array = "";
			$html .= "static \$".$type." = array(";
			foreach ($tables as $table) {
				$array .= "\n\t\tarray('".$table."'),";
			}
			$html .= substr($array, 0, -1)."\n\t);\n\t";
		}
		if (!empty($config['null'])) {
			$html .= "static \$validates_presence_of = array(";
			foreach ($config['null'] as $field)
				$html .= "\n\t\tarray('".$field."', 'message' => 'can\'t be blank or empty'),";
			$html = substr($html, 0, -1);
		}
		$html .= "\n\t);\n}";
		$this->html_model = $html;
	}

	public function getHtmlSidebar()
	{
		return $this->html_sidebar;
	}

	public function setHtmlSidebar($table)
	{
		$this->html_sidebar = "<li class='nav-item'><?= Template::link('".ucwords(str_replace("_", " ", $table))."', ROOT.'".$table."', ['class' => 'nav-link text-primary']); ?></li>\n";
	}

	public function getHtmlIndex()
	{
		return $this->html_index;
	}

	public function setHtmlIndex($table, $arrayFields)
	{
		$html_th = "";
		$html_td = "";
		$html 	 = "<?php require_once VIEWS.'header.php'; ?>\n<?php require_once ELEMENTS.'message.php'; ?>\n<div class='row'>\n\t<div class='col-7'>\n\t\t<div class='form-group'>\n\t\t\t<?= Template::link('New', ROOT.'".$table."'.DS.'create', ['class' => 'btn btn-primary']); ?>\n\t\t</div>\n\t</div>\n\t<div class='col-2'>\n\t\t<div class='form-group'>\n\t\t\t<?= Template::select('num_rows', ['class' => 'form-control num-rows'], ['label' => false, 'options' => array('5' => '5', '10' => '10', '15' => '15')]); ?>\n\t\t</div>\n\t</div>\n\t<div class='col-3'>\n\t\t<div class='form-group'>\n\t\t\t<?= Template::input('Search', ['class' => 'form-control search-table', 'placeholder' => 'Search'], ['label' => false]); ?>\n\t\t</div>\n\t</div>\n</div>\n";
		foreach ($arrayFields as $field) {
			$html_th .= "\n\t\t\t\t\t\t<th>".strtoupper(str_replace("_", " ", $field->field))."</th>";
			if($field->type == 'datetime')
				$format = "->format('d/m/Y H:i:s')";
			else if($field->type == 'date')
				$format = "->format('d/m/Y')";
			else
				$format = "";
			$html_td .= "\n\t\t\t\t\t\t<td><?= \$row->".$field->field.$format." ?></td>";
		}
		$html_th .= "\n\t\t\t\t\t\t<th>ACTION</th>";
		$html_td .= "\n\t\t\t\t\t\t<td><?= Template::link('&#9998;', ROOT.'".$table."'.DS.'edit'.DS.\$row->id, ['class' => 'link mx-1', 'title' => 'Edit']); ?>\n\t\t\t\t\t\t\t<?= Template::link('&#10060;', null, [\n\t\t\t\t\t\t\t\t'title' 	  => 'Delete',\n\t\t\t\t\t\t\t\t'class' 	  => 'link mx-1 delete',\n\t\t\t\t\t\t\t\t'data-title'  => 'Delete '.\$row->id.'?',\n\t\t\t\t\t\t\t\t'data-body'   => 'Do you want to delete the '.\$row->id.'?',\n\t\t\t\t\t\t\t\t'data-footer' => json_encode(array(\n\t\t\t\t\t\t\t\t\t'Delete' => ['class'=>'btn btn-danger','href'=> ROOT.'".$table."'.DS.'delete'.DS.\$row->id],\n\t\t\t\t\t\t\t\t\t'Cancel' => ['class'=>'btn btn-light', 'data-dismiss'=>'modal']\n\t\t\t\t\t\t\t\t))\n\t\t\t\t\t\t\t]); ?>\n\t\t\t\t\t\t</td>";
		$html 	 .= "<div class='row'>\n\t<div class='col-12'>\n\t\t<div class='table-responsive'>\n\t\t\t<table class='table table-sm'>\n\t\t\t\t<thead class='thead-light'>\n\t\t\t\t\t<tr>".$html_th."\n\t\t\t\t\t</tr>\n\t\t\t\t</thead>\n\t\t\t\t<tbody>\n\t\t\t\t<?php foreach (\$".$table." as \$row) { ?>\n\t\t\t\t\t<tr>".$html_td."\n\t\t\t\t\t</tr>\n\t\t\t\t<?php } ?>\n\t\t\t\t</tbody>\n\t\t\t</table>\n\t\t</div>\n\t</div>\n</div>\n";
		$html 	 .= "<?php require_once VIEWS.'footer.php'; ?>";
		$this->html_index = $html;
	}

	public function getHtmlCreate()
	{
		return $this->html_create;
	}

	public function setHtmlCreate($model_name, $table, $arrayFields)
	{
		$count = 1;
		$html  = "<?php require_once VIEWS.'header.php'; ?>\n<?php require_once ELEMENTS.'message.php'; ?>\n<h1>New ".$model_name."</h1>\n<hr>\n<form method='post' action='<?= ROOT.'".$table."'.DS.\$_mvc->getAction().DS.\$_mvc->getParameters() ?>'>\n\t<div class='row'>";
		foreach ($arrayFields as $field) {
			$type_config = explode("(", $field->type); 			/* varchar(20) = array('varchar','20)') */ 
			$type 		 = reset($type_config);					/* varchar */
			$value 		 = substr(end($type_config), 0, -1);	/* 20 */
			$comments	 = explode(";", $field->comment);
			$required	 = ($field->null == 'NO') ? 'true' : 'false';
			if($field->field != 'id'){
				switch ($type) {
					case 'int':
						$default = empty($field->default) ? "\$".$table."->".$field->field : "\$".$table."->".$field->field." ?: '".$field->default."'" ;
						$html 	.= "\n\t\t<div class='col-3'>\n\t\t\t<div class='form-group'>\n\t\t\t\t<?= Template::input('".$field->field."', ['type' => 'number', 'class' => 'form-control', 'maxlength' => '".$value."', 'value' => ".$default.", 'required' => ".$required."]); ?>\n\t\t\t</div>\n\t\t</div>";
						break;
					case 'char':
					case 'varchar':
						$default = empty($field->default) ? "\$".$table."->".$field->field : "\$".$table."->".$field->field." ?: '".$field->default."'" ;
						$html 	.= "\n\t\t<div class='col-3'>\n\t\t\t<div class='form-group'>\n\t\t\t\t<?= Template::input('".$field->field."', ['type' => 'text', 'class' => 'form-control', 'maxlength' => '".$value."', 'value' => ".$default.", 'required' => ".$required."]); ?>\n\t\t\t</div>\n\t\t</div>";
						break;
					case 'enum':
						$options = strstr($value, ',') ? "array(".implode(', ', array_map(function ($k, $v) { return sprintf("%s=>'%s'", $v, $k); }, $comments, explode(",", $value))).")" : substr($value, 1, -1) ;
						$html   .= "\n\t\t<div class='col-3'>\n\t\t\t<div class='form-group'>\n\t\t\t\t<?= Template::select('".$field->field."',\n\t\t\t\t\t['class' => 'form-control', 'required' => ".$required."],\n\t\t\t\t\t['label' => true, 'selected' => \$".$table."->".$field->field.", 'options' => ".$options."]\n\t\t\t\t); ?>\n\t\t\t</div>\n\t\t</div>";
						break;
					case 'text':
						$html .= "\n\t\t<div class='col-12'>\n\t\t\t<div class='form-group'>\n\t\t\t\t<?= Template::textarea('".$field->field."', ['rows' => 5, 'class' => 'form-control', 'required' => ".$required."], ['label' => true, 'value' => \$".$table."->".$field->field."]); ?>\n\t\t\t</div>\n\t\t</div>";
						break;
					case 'date':
						$default = empty($field->default) ? "(\$".$table."->".$field->field.") ? \$".$table."->".$field->field."->format('Y-m-d') : ''" : "(\$".$table."->".$field->field.") ? \$".$table."->".$field->field."->format('Y-m-d') : date('Y-m-d')" ;
						$html   .= "\n\t\t<div class='col-3'>\n\t\t\t<div class='form-group'>\n\t\t\t\t<?= Template::input('".$field->field."', ['type' => 'date', 'class' => 'form-control', 'value' => ".$default.", 'required' => ".$required."]); ?>\n\t\t\t</div>\n\t\t</div>";
						break;
					case 'time':
						$default = empty($field->default) ? "\$".$table."->".$field->field : "\$".$table."->".$field->field." ?: '".$field->default."'" ;
						$html .= "\n\t\t<div class='col-3'>\n\t\t\t<div class='form-group'>\n\t\t\t\t<?= Template::input('".$field->field."', ['type' => 'time', 'class' => 'form-control', 'value' => ".$default.", 'required' => ".$required."]); ?>\n\t\t\t</div>\n\t\t</div>";
						break;
						
					case 'datetime':
						$cur_timestamp = ($field->default == 'CURRENT_TIMESTAMP') ? date('Y-m-d\TH:i') : '';
						$default 	   = empty($field->default) ? "(\$".$table."->date) ? \$".$table."->".$field->field."->format('Y-m-d\TH:i') : ''" : "(\$".$table."->date) ? \$".$table."->".$field->field."->format('Y-m-d\TH:i') : date('Y-m-d\TH:i')" ;
						$html 		  .= "\n\t\t<div class='col-3'>\n\t\t\t<div class='form-group'>\n\t\t\t\t<?= Template::input('".$field->field."', ['type' => 'datetime-local', 'class' => 'form-control', 'value' => ".$default.", 'required' => ".$required."]); ?>\n\t\t\t</div>\n\t\t</div>";
						break;
				}
			}
		}
		$html .= "\n\t</div>\n\t<hr>\n\t<div class='row'>\n\t\t<div class='col-12'>\n\t\t\t<div class='form-group'>\n\t\t\t\t<?= Template::button('Save', ['class' => 'btn btn-success']); ?>\n\t\t\t\t<?= Template::link('Back', ROOT.'".$table."', ['class' => 'btn btn-warning']); ?>\n\t\t\t</div>\n\t\t</div>\n\t</div>\n</form>\n";
		$html .= "<?php require_once VIEWS.'footer.php'; ?>";
		$this->html_create = $html;
	}	
}
