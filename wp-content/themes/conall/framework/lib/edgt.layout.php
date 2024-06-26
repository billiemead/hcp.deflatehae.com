<?php

/*
   Interface: iConallEdgeInterfaceLayoutNode
   A interface that implements Layout Node methods
*/
interface iConallEdgeInterfaceLayoutNode
{
	public function hasChidren();
	public function getChild($key);
	public function addChild($key, $value);
}

/*
   Interface: iConallEdgeInterfaceRender
   A interface that implements Render methods
*/
interface iConallEdgeInterfaceRender
{
	public function render($factory);
}

/*
   Class: ConallEdgeClassPanel
   A class that initializes Edge Panel
*/
class ConallEdgeClassPanel implements iConallEdgeInterfaceLayoutNode, iConallEdgeInterfaceRender {

	public $children;
	public $title;
	public $name;
	public $hidden_property;
	public $hidden_value;
	public $hidden_values;

	function __construct($title_label="",$name="",$hidden_property="",$hidden_value="",$hidden_values=array()) {
		$this->children = array();
		$this->title = $title_label;
		$this->name = $name;
		$this->hidden_property = $hidden_property;
		$this->hidden_value = $hidden_value;
		$this->hidden_values = $hidden_values;
	}

	public function hasChidren() {
		return (count($this->children) > 0)?true:false;
	}

	public function getChild($key) {
		return $this->children[$key];
	}

	public function addChild($key, $value) {
		$this->children[$key] = $value;
	}

	public function render($factory) {
		$hidden = false;
		if (!empty($this->hidden_property)){
			if (conall_edge_option_get_value($this->hidden_property)==$this->hidden_value)
				$hidden = true;
			else {
				foreach ($this->hidden_values as $value) {
					if (conall_edge_option_get_value($this->hidden_property)==$value)
						$hidden = true;

				}
			}
		}
		?>
		<div class="edgtf-page-form-section-holder" id="edgtf_<?php echo esc_attr($this->name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>
			<h3 class="edgtf-page-section-title"><?php echo esc_html($this->title); ?></h3>
			<?php
			foreach ($this->children as $child) {
				$this->renderChild($child, $factory);
			}
			?>
		</div>
	<?php
	}

	public function renderChild(iConallEdgeInterfaceRender $child, $factory) {
		$child->render($factory);
	}
}

/*
   Class: ConallEdgeClassContainer
   A class that initializes Edge Container
*/
class ConallEdgeClassContainer implements iConallEdgeInterfaceLayoutNode, iConallEdgeInterfaceRender {

	public $children;
	public $name;
	public $hidden_property;
	public $hidden_value;
	public $hidden_values;

	function __construct($name="",$hidden_property="",$hidden_value="",$hidden_values=array()) {
		$this->children = array();
		$this->name = $name;
		$this->hidden_property = $hidden_property;
		$this->hidden_value = $hidden_value;
		$this->hidden_values = $hidden_values;
	}

	public function hasChidren() {
		return (count($this->children) > 0)?true:false;
	}

	public function getChild($key) {
		return $this->children[$key];
	}

	public function addChild($key, $value) {
		$this->children[$key] = $value;
	}

	public function render($factory) {
		$hidden = false;

		if (!empty($this->hidden_property)){
			if (conall_edge_option_get_value($this->hidden_property)==$this->hidden_value)
				$hidden = true;
			else {
				foreach ($this->hidden_values as $value) {
					if (conall_edge_option_get_value($this->hidden_property)==$value)
						$hidden = true;
				}
			}
		}
		?>
		<div class="edgtf-page-form-container-holder" id="edgtf_<?php echo esc_attr($this->name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>
			<?php
			foreach ($this->children as $child) {
				$this->renderChild($child, $factory);
			}
			?>
		</div>
	<?php
	}

	public function renderChild(iConallEdgeInterfaceRender $child, $factory) {
		$child->render($factory);
	}
}


/*
   Class: ConallEdgeClassContainerNoStyle
   A class that initializes Edge Container without css classes
*/
class ConallEdgeClassContainerNoStyle implements iConallEdgeInterfaceLayoutNode, iConallEdgeInterfaceRender {

	public $children;
	public $name;
	public $hidden_property;
	public $hidden_value;
	public $hidden_values;

	function __construct($name="",$hidden_property="",$hidden_value="",$hidden_values=array()) {
		$this->children = array();
		$this->name = $name;
		$this->hidden_property = $hidden_property;
		$this->hidden_value = $hidden_value;
		$this->hidden_values = $hidden_values;
	}

	public function hasChidren() {
		return (count($this->children) > 0)?true:false;
	}

	public function getChild($key) {
		return $this->children[$key];
	}

	public function addChild($key, $value) {
		$this->children[$key] = $value;
	}

	public function render($factory) {
		$hidden = false;
		if (!empty($this->hidden_property)){
			if (conall_edge_option_get_value($this->hidden_property)==$this->hidden_value)
				$hidden = true;
			else {
				foreach ($this->hidden_values as $value) {
					if (conall_edge_option_get_value($this->hidden_property)==$value)
						$hidden = true;

				}
			}
		}
		?>
		<div id="edgtf_<?php echo esc_attr($this->name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>
			<?php
			foreach ($this->children as $child) {
				$this->renderChild($child, $factory);
			}
			?>
		</div>
	<?php
	}

	public function renderChild(iConallEdgeInterfaceRender $child, $factory) {
		$child->render($factory);
	}
}

/*
   Class: ConallEdgeClassGroup
   A class that initializes Edge Group
*/
class ConallEdgeClassGroup implements iConallEdgeInterfaceLayoutNode, iConallEdgeInterfaceRender {

	public $children;
	public $title;
	public $description;

	function __construct($title_label="",$description="") {
		$this->children = array();
		$this->title = $title_label;
		$this->description = $description;
	}

	public function hasChidren() {
		return (count($this->children) > 0)?true:false;
	}

	public function getChild($key) {
		return $this->children[$key];
	}

	public function addChild($key, $value) {
		$this->children[$key] = $value;
	}

	public function render($factory) {
		?>

		<div class="edgtf-page-form-section">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($this->title); ?></h4>

				<p><?php echo esc_html($this->description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<?php
					foreach ($this->children as $child) {
						$this->renderChild($child, $factory);
					}
					?>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php
	}

	public function renderChild(iConallEdgeInterfaceRender $child, $factory) {
		$child->render($factory);
	}
}

/*
   Class: ConallEdgeClassNotice
   A class that initializes Edge Notice
*/
class ConallEdgeClassNotice implements iConallEdgeInterfaceRender {

	public $children;
	public $title;
	public $description;
	public $notice;
	public $hidden_property;
	public $hidden_value;
	public $hidden_values;

	function __construct($title_label="",$description="",$notice="",$hidden_property="",$hidden_value="",$hidden_values=array()) {
		$this->children = array();
		$this->title = $title_label;
		$this->description = $description;
		$this->notice = $notice;
		$this->hidden_property = $hidden_property;
		$this->hidden_value = $hidden_value;
		$this->hidden_values = $hidden_values;
	}

	public function render($factory) {
		$hidden = false;
		if (!empty($this->hidden_property)){
			if (conall_edge_option_get_value($this->hidden_property)==$this->hidden_value)
				$hidden = true;
			else {
				foreach ($this->hidden_values as $value) {
					if (conall_edge_option_get_value($this->hidden_property)==$value)
						$hidden = true;

				}
			}
		}
		?>

		<div class="edgtf-page-form-section"<?php if ($hidden) { ?> style="display: none"<?php } ?>>


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($this->title); ?></h4>

				<p><?php echo esc_html($this->description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="alert alert-warning">
						<?php echo esc_html($this->notice); ?>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php
	}
}

/*
   Class: ConallEdgeClassRow
   A class that initializes Edge Row
*/
class ConallEdgeClassRow implements iConallEdgeInterfaceLayoutNode, iConallEdgeInterfaceRender {

	public $children;
	public $next;

	function __construct($next=false) {
		$this->children = array();
		$this->next = $next;
	}

	public function hasChidren() {
		return (count($this->children) > 0)?true:false;
	}

	public function getChild($key) {
		return $this->children[$key];
	}

	public function addChild($key, $value) {
		$this->children[$key] = $value;
	}

	public function render($factory) {
		?>
		<div class="row<?php if ($this->next) echo " next-row"; ?>">
			<?php
			foreach ($this->children as $child) {
				$this->renderChild($child, $factory);
			}
			?>
		</div>
	<?php
	}

	public function renderChild(iConallEdgeInterfaceRender $child, $factory) {
		$child->render($factory);
	}
}

/*
   Class: ConallEdgeClassTitle
   A class that initializes Edge Title
*/
#[AllowDynamicProperties]
class ConallEdgeClassTitle implements iConallEdgeInterfaceRender {
	private $name;
	private $title;
	public $hidden_property;
	public $hidden_values = array();

	function __construct($name="",$title_label="",$hidden_property="",$hidden_value="") {
		$this->title = $title_label;
		$this->name = $name;
		$this->hidden_property = $hidden_property;
		$this->hidden_value = $hidden_value;
	}

	public function render($factory) {
		$hidden = false;
		if (!empty($this->hidden_property)){
			if (conall_edge_option_get_value($this->hidden_property)==$this->hidden_value)
				$hidden = true;
		}
		?>
		<h5 class="edgtf-page-section-subtitle" id="edgtf_<?php echo esc_attr($this->name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>><?php echo esc_html($this->title); ?></h5>
	<?php
	}
}

/*
   Class: ConallEdgeClassField
   A class that initializes Edge Field
*/
class ConallEdgeClassField implements iConallEdgeInterfaceRender {
	private $type;
	private $name;
	private $default_value;
	private $label;
	private $description;
	private $options = array();
	private $args = array();
	public $hidden_property;
	public $hidden_values = array();


	function __construct($type,$name,$default_value="",$label="",$description="", $options = array(), $args = array(),$hidden_property="", $hidden_values = array()) {
		global $conall_edge_framework;
		$this->type = $type;
		$this->name = $name;
		$this->default_value = $default_value;
		$this->label = $label;
		$this->description = $description;
		$this->options = $options;
		$this->args = $args;
		$this->hidden_property = $hidden_property;
		$this->hidden_values = $hidden_values;
		$conall_edge_framework->edgtOptions->addOption($this->name,$this->default_value, $type);
	}

	public function render($factory) {
		$hidden = false;
		if (!empty($this->hidden_property)){
			foreach ($this->hidden_values as $value) {
				if (conall_edge_option_get_value($this->hidden_property)==$value)
					$hidden = true;

			}
		}
		$factory->render( $this->type, $this->name, $this->label, $this->description, $this->options, $this->args, $hidden );
	}
}

/*
   Class: ConallEdgeClassMetaField
   A class that initializes Edge Meta Field
*/
class ConallEdgeClassMetaField implements iConallEdgeInterfaceRender {
	private $type;
	private $name;
	private $default_value;
	private $label;
	private $description;
	private $options = array();
	private $args = array();
	public $hidden_property;
	public $hidden_values = array();


	function __construct($type,$name,$default_value="",$label="",$description="", $options = array(), $args = array(),$hidden_property="", $hidden_values = array()) {
		global $conall_edge_framework;
		$this->type = $type;
		$this->name = $name;
		$this->default_value = $default_value;
		$this->label = $label;
		$this->description = $description;
		$this->options = $options;
		$this->args = $args;
		$this->hidden_property = $hidden_property;
		$this->hidden_values = $hidden_values;
		$conall_edge_framework->edgtMetaBoxes->addOption($this->name,$this->default_value);
	}

	public function render($factory) {
		$hidden = false;
		if (!empty($this->hidden_property)){
			foreach ($this->hidden_values as $value) {
				if (conall_edge_option_get_value($this->hidden_property)==$value)
					$hidden = true;

			}
		}
		$factory->render( $this->type, $this->name, $this->label, $this->description, $this->options, $this->args, $hidden );
	}
}

abstract class ConallEdgeClassFieldType {

	abstract public function render( $name, $label="",$description="", $options = array(), $args = array(), $hidden = false );

}

class ConallEdgeClassFieldText extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$col_width = 12;
		if(isset($args["col_width"])) {
            $col_width = $args["col_width"];
        }

        $suffix = !empty($args['suffix']) ? $args['suffix'] : false;

		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-<?php echo esc_attr($col_width); ?>">
                            <?php if($suffix) : ?>
                            <div class="input-group">
                            <?php endif; ?>
                                <input type="text"
                                    class="form-control edgtf-input edgtf-form-element"
                                    name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(htmlspecialchars(conall_edge_option_get_value($name))); ?>"
                                    />
                                <?php if($suffix) : ?>
                                    <div class="input-group-addon"><?php echo esc_html($args['suffix']); ?></div>
                                <?php endif; ?>
                            <?php if($suffix) : ?>
                            </div>
                            <?php endif; ?>

                        </div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldTextSimple extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {

		$suffix = !empty($args['suffix']) ? $args['suffix'] : false;

		?>


		<div class="col-lg-3" id="edgtf_<?php echo esc_attr($name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>
			<em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
			<?php if($suffix) : ?>
			<div class="input-group">
            <?php endif; ?>
				<input type="text"
				   class="form-control edgtf-input edgtf-form-element"
				   name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(htmlspecialchars(conall_edge_option_get_value($name))); ?>"
				   />
				<?php if($suffix) : ?>
					<div class="input-group-addon"><?php echo esc_html($args['suffix']); ?></div>
				<?php endif; ?>
			<?php if($suffix) : ?>
			</div>
			<?php endif; ?>
		</div>
	<?php

	}

}

class ConallEdgeClassFieldTextArea extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		?>

		<div class="edgtf-page-form-section">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->


			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<textarea class="form-control edgtf-form-element"
									  name="<?php echo esc_attr($name); ?>"
									  rows="5"><?php echo esc_html(htmlspecialchars(conall_edge_option_get_value($name))); ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldTextAreaSimple extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		?>

		<div class="col-lg-3">
			<em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
			<textarea class="form-control edgtf-form-element"
					  name="<?php echo esc_attr($name); ?>"
					  rows="5"><?php echo esc_html(conall_edge_option_get_value($name)); ?></textarea>
		</div>
	<?php

	}

}

class ConallEdgeClassFieldColor extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<input type="text" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>" class="my-color-field"/>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldColorSimple extends ConallEdgeClassFieldType {
	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) { ?>
		<div class="col-lg-3" id="edgtf_<?php echo esc_attr($name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>
			<em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
			<input type="text" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>" class="my-color-field"/>
		</div>
	<?php
	}
}

class ConallEdgeClassFieldImage extends ConallEdgeClassFieldType {
	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) { ?>
		<div class="edgtf-page-form-section">
			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="edgtf-media-uploader">
								<div<?php if (!conall_edge_option_has_value($name)) { ?> style="display: none"<?php } ?>
									class="edgtf-media-image-holder">
									<img src="<?php if (conall_edge_option_has_value($name)) { echo esc_url(conall_edge_get_attachment_thumb_url(conall_edge_option_get_value($name))); } ?>"
										 class="edgtf-media-image img-thumbnail"/>
								</div>
								<div style="display: none"
									 class="edgtf-media-meta-fields">
									<input type="hidden" class="edgtf-media-upload-url"
										   name="<?php echo esc_attr($name); ?>"
										   value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
								</div>
								<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
								   href="javascript:void(0)"
								   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
								   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
								<a style="display: none;" href="javascript: void(0)"
								   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldImageSimple extends ConallEdgeClassFieldType {

    public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
        ?>


        <div class="col-lg-3" id="edgtf_<?php echo esc_attr($name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>
            <em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
            <div class="edgtf-media-uploader">
                <div<?php if (!conall_edge_option_has_value($name)) { ?> style="display: none"<?php } ?>
                    class="edgtf-media-image-holder">
                    <img src="<?php if (conall_edge_option_has_value($name)) { echo esc_url(conall_edge_get_attachment_thumb_url(conall_edge_option_get_value($name))); } ?>"
                         class="edgtf-media-image img-thumbnail"/>
                </div>
                <div style="display: none"
                     class="edgtf-media-meta-fields">
                    <input type="hidden" class="edgtf-media-upload-url"
                           name="<?php echo esc_attr($name); ?>"
                           value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
                </div>
                <a class="edgtf-media-upload-btn btn btn-sm btn-primary"
                   href="javascript:void(0)"
                   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
                   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
                <a style="display: none;" href="javascript: void(0)"
                   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
            </div>
        </div>
    <?php

    }

}

class ConallEdgeClassFieldFont extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		global $conall_edge_fonts_array;
		?>

		<div class="edgtf-page-form-section">
			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>
				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->
			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-3">
							<select class="form-control edgtf-form-element"
									name="<?php echo esc_attr($name); ?>">
								<option value="-1"><?php esc_html_e( 'Default', 'conall' ); ?></option>
								<?php foreach($conall_edge_fonts_array as $fontArray) { ?>
									<option <?php if (conall_edge_option_get_value($name) == str_replace(' ', '+', $fontArray["family"])) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr(str_replace(' ', '+', $fontArray["family"])); ?>"><?php echo esc_html($fontArray["family"]); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldFontSimple extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		global $conall_edge_fonts_array;
		?>
		<div class="col-lg-3">
			<em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
			<select class="form-control edgtf-form-element"
					name="<?php echo esc_attr($name); ?>">
				<option value="-1"><?php esc_html_e( 'Default', 'conall' ); ?></option>
				<?php foreach($conall_edge_fonts_array as $fontArray) { ?>
					<option <?php if (conall_edge_option_get_value($name) == str_replace(' ', '+', $fontArray["family"])) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr(str_replace(' ', '+', $fontArray["family"])); ?>"><?php echo esc_html($fontArray["family"]); ?></option>
				<?php } ?>
			</select>
		</div>
	<?php

	}

}

class ConallEdgeClassFieldSelect extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$show = array();
		if(isset($args["show"]))
			$show = $args["show"];
		$hide = array();
		if(isset($args["hide"]))
			$hide = $args["hide"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>" <?php if ($hidden) { ?> style="display: none"<?php } ?>>


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-3">
							<select class="form-control edgtf-form-element<?php if ($dependence) { echo " dependence"; } ?>"
								<?php foreach($show as $key=>$value) { ?>
									data-show-<?php echo str_replace(' ', '',$key); ?>="<?php echo esc_attr($value); ?>"
								<?php } ?>
								<?php foreach($hide as $key=>$value) { ?>
									data-hide-<?php echo str_replace(' ', '',$key); ?>="<?php echo esc_attr($value); ?>"
								<?php } ?>
									name="<?php echo esc_attr($name); ?>">
								<?php foreach($options as $key=>$value) { if ($key == "-1") $key = ""; ?>
									<option <?php if (conall_edge_option_get_value($name) == $key) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldSelectBlank extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$show = array();
		if(isset($args["show"]))
			$show = $args["show"];
		$hide = array();
		if(isset($args["hide"]))
			$hide = $args["hide"];
		?>

		<div class="edgtf-page-form-section"<?php if ($hidden) { ?> style="display: none"<?php } ?>>


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-3">
							<select class="form-control edgtf-form-element<?php if ($dependence) { echo " dependence"; } ?>"
								<?php foreach($show as $key=>$value) { ?>
									data-show-<?php echo str_replace(' ', '',$key); ?>="<?php echo esc_attr($value); ?>"
								<?php } ?>
								<?php foreach($hide as $key=>$value) { ?>
									data-hide-<?php echo str_replace(' ', '',$key); ?>="<?php echo esc_attr($value); ?>"
								<?php } ?>
									name="<?php echo esc_attr($name); ?>">
								<option <?php if (conall_edge_option_get_value($name) == "") { echo "selected='selected'"; } ?>  value=""></option>
								<?php foreach($options as $key=>$value) { if ($key == "-1") $key = ""; ?>
									<option <?php if (conall_edge_option_get_value($name) == $key) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldSelectSimple extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
        $dependence = false;
        if(isset($args["dependence"]))
            $dependence = true;
        $show = array();
        if(isset($args["show"]))
            $show = $args["show"];
        $hide = array();
        if(isset($args["hide"]))
            $hide = $args["hide"];
        ?>


		<div class="col-lg-3">
			<em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
            <select class="form-control edgtf-form-element<?php if ($dependence) { echo " dependence"; } ?>"
                <?php foreach($show as $key=>$value) { ?>
                    data-show-<?php echo str_replace(' ', '',$key); ?>="<?php echo esc_attr($value); ?>"
                <?php } ?>
                <?php foreach($hide as $key=>$value) { ?>
                    data-hide-<?php echo str_replace(' ', '',$key); ?>="<?php echo esc_attr($value); ?>"
                <?php } ?>
                    name="<?php echo esc_attr($name); ?>">
                <option <?php if (conall_edge_option_get_value($name) == "") { echo "selected='selected'"; } ?>  value=""></option>
                <?php foreach($options as $key=>$value) { if ($key == "-1") $key = ""; ?>
                    <option <?php if (conall_edge_option_get_value($name) == $key) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php } ?>
            </select>
		</div>
	<?php

	}

}

class ConallEdgeClassFieldSelectBlankSimple extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
        $dependence = false;
        if(isset($args["dependence"]))
            $dependence = true;
        $show = array();
        if(isset($args["show"]))
            $show = $args["show"];
        $hide = array();
        if(isset($args["hide"]))
            $hide = $args["hide"];
        ?>


		<div class="col-lg-3">
			<em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
            <select class="form-control edgtf-form-element<?php if ($dependence) { echo " dependence"; } ?>"
                <?php foreach($show as $key=>$value) { ?>
                    data-show-<?php echo str_replace(' ', '',$key); ?>="<?php echo esc_attr($value); ?>"
                <?php } ?>
                <?php foreach($hide as $key=>$value) { ?>
                    data-hide-<?php echo str_replace(' ', '',$key); ?>="<?php echo esc_attr($value); ?>"
                <?php } ?>
                    name="<?php echo esc_attr($name); ?>">
                <option <?php if (conall_edge_option_get_value($name) == "") { echo "selected='selected'"; } ?>  value=""></option>
                <?php foreach($options as $key=>$value) { if ($key == "-1") $key = ""; ?>
                    <option <?php if (conall_edge_option_get_value($name) == $key) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                <?php } ?>
            </select>
		</div>
	<?php

	}

}

class ConallEdgeClassFieldYesNo extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "yes") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "no") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_yesno" value="yes"<?php if (conall_edge_option_get_value($name) == "yes") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_yesno" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}
}

class ConallEdgeClassFieldYesNoSimple extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="col-lg-3">
			<em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
			<p class="field switch">
				<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
					   class="cb-enable<?php if (conall_edge_option_get_value($name) == "yes") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
				<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
					   class="cb-disable<?php if (conall_edge_option_get_value($name) == "no") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
				<input type="checkbox" id="checkbox" class="checkbox"
					   name="<?php echo esc_attr($name); ?>_yesno" value="yes"<?php if (conall_edge_option_get_value($name) == "yes") { echo " selected"; } ?>/>
				<input type="hidden" class="checkboxhidden_yesno" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
			</p>
		</div>
	<?php

	}
}

class ConallEdgeClassFieldOnOff extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">

							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "on") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('On', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "off") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Off', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_onoff" value="on"<?php if (conall_edge_option_get_value($name) == "on") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_onoff" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldPortfolioFollow extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "portfolio_single_follow") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "portfolio_single_no_follow") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_portfoliofollow" value="portfolio_single_follow"<?php if (conall_edge_option_get_value($name) == "portfolio_single_follow") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_portfoliofollow" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldZeroOne extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">

							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "1") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "0") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_zeroone" value="1"<?php if (conall_edge_option_get_value($name) == "1") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_zeroone" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldImageVideo extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>
		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">

			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch switch-type">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "image") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Image', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "video") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Video', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_imagevideo" value="image"<?php if (conall_edge_option_get_value($name) == "image") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_imagevideo" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->
		</div>
	<?php
	}
}

class ConallEdgeClassFieldYesEmpty extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "yes") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_yesempty" value="yes"<?php if (conall_edge_option_get_value($name) == "yes") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_yesempty" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldFlagPage extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "page") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_flagpage" value="page"<?php if (conall_edge_option_get_value($name) == "page") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_flagpage" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldFlagPost extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {

		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "post") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_flagpost" value="post"<?php if (conall_edge_option_get_value($name) == "post") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_flagpost" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldFlagMedia extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "attachment") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_flagmedia" value="attachment"<?php if (conall_edge_option_get_value($name) == "attachment") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_flagmedia" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldFlagPortfolio extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "portfolio_page") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_flagportfolio" value="portfolio_page"<?php if (conall_edge_option_get_value($name) == "portfolio_page") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_flagportfolio" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldFlagProduct extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$dependence = false;
		if(isset($args["dependence"]))
			$dependence = true;
		$dependence_hide_on_yes = "";
		if(isset($args["dependence_hide_on_yes"]))
			$dependence_hide_on_yes = $args["dependence_hide_on_yes"];
		$dependence_show_on_yes = "";
		if(isset($args["dependence_show_on_yes"]))
			$dependence_show_on_yes = $args["dependence_show_on_yes"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->



			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<p class="field switch">
								<label data-hide="<?php echo esc_attr($dependence_hide_on_yes); ?>" data-show="<?php echo esc_attr($dependence_show_on_yes); ?>"
									   class="cb-enable<?php if (conall_edge_option_get_value($name) == "product") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('Yes', 'conall') ?></span></label>
								<label data-hide="<?php echo esc_attr($dependence_show_on_yes); ?>" data-show="<?php echo esc_attr($dependence_hide_on_yes); ?>"
									   class="cb-disable<?php if (conall_edge_option_get_value($name) == "") { echo " selected"; } ?><?php if ($dependence) { echo " dependence"; } ?>"><span><?php esc_html_e('No', 'conall') ?></span></label>
								<input type="checkbox" id="checkbox" class="checkbox"
									   name="<?php echo esc_attr($name); ?>_flagproduct" value="product"<?php if (conall_edge_option_get_value($name) == "product") { echo " selected"; } ?>/>
								<input type="hidden" class="checkboxhidden_flagproduct" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>
							</p>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldRange extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$range_min = 0;
		$range_max = 1;
		$range_step = 0.01;
		$range_decimals = 2;
		if(isset($args["range_min"]))
			$range_min = $args["range_min"];
		if(isset($args["range_max"]))
			$range_max = $args["range_max"];
		if(isset($args["range_step"]))
			$range_step = $args["range_step"];
		if(isset($args["range_decimals"]))
			$range_decimals = $args["range_decimals"];
		?>

		<div class="edgtf-page-form-section">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="edgtf-slider-range-wrapper">
								<div class="form-inline">
									<input type="text"
										   class="form-control edgtf-form-element edgtf-form-element-xsmall pull-left edgtf-slider-range-value"
										   name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>

									<div class="edgtf-slider-range small"
										 data-step="<?php echo esc_attr($range_step); ?>" data-min="<?php echo esc_attr($range_min); ?>" data-max="<?php echo esc_attr($range_max); ?>" data-decimals="<?php echo esc_attr($range_decimals); ?>" data-start="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"></div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}

}

class ConallEdgeClassFieldRangeSimple extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		?>

		<div class="col-lg-3" id="edgtf_<?php echo esc_attr($name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>
			<div class="edgtf-slider-range-wrapper">
				<div class="form-inline">
					<em class="edgtf-field-description"><?php echo esc_html($label); ?></em>
					<input type="text"
						   class="form-control edgtf-form-element edgtf-form-element-xxsmall pull-left edgtf-slider-range-value"
						   name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"/>

					<div class="edgtf-slider-range xsmall"
						 data-step="0.01" data-max="1" data-start="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"></div>
				</div>

			</div>
		</div>
	<?php

	}

}

class ConallEdgeClassFieldRadio extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {

		$checked = "";
		if ($default_value == $value)
			$checked = "checked";
		$html = '<input type="radio" name="'.$name.'" value="'.$default_value.'" '.$checked.' /> '.$label.'<br />';
		echo wp_kses($html, array(
			'input' => array(
				'type' => true,
				'name' => true,
				'value' => true,
				'checked' => true
			),
			'br' => true
		));
	}
}

class ConallEdgeClassFieldRadioGroup extends ConallEdgeClassFieldType {

    public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
        $dependence = isset($args["dependence"]) && $args["dependence"] ? true : false;
        $show = !empty($args["show"]) ? $args["show"] : array();
        $hide = !empty($args["hide"]) ? $args["hide"] : array();

        $use_images = isset($args["use_images"]) && $args["use_images"] ? true : false;
        $hide_labels = isset($args["hide_labels"]) && $args["hide_labels"] ? true : false;
        $hide_radios = $use_images ? 'display: none' : '';
        $checked_value = conall_edge_option_get_value($name);
        ?>

        <div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>" <?php if ($hidden) { ?> style="display: none"<?php } ?>>

            <div class="edgtf-field-desc">
                <h4><?php echo esc_html($label); ?></h4>

                <p><?php echo esc_html($description); ?></p>
            </div>
            <!-- close div.edgtf-field-desc -->

            <div class="edgtf-section-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if(is_array($options) && count($options)) { ?>
                                <div class="edgtf-radio-group-holder <?php if($use_images) echo "with-images"; ?>">
                                    <?php foreach($options as $key => $atts) {
                                        $selected = false;
                                        if($checked_value == $key) {
                                            $selected = true;
                                        }

                                        $show_val = "";
                                        $hide_val = "";
                                        if($dependence) {
                                            if(array_key_exists($key, $show)) {
                                                $show_val = $show[$key];
                                            }

                                            if(array_key_exists($key, $hide)) {
                                                $hide_val = $hide[$key];
                                            }
                                        }
                                    ?>
                                        <label class="radio-inline">
                                            <input
                                                <?php echo conall_edge_get_inline_attr($show_val, 'data-show'); ?>
                                                <?php echo conall_edge_get_inline_attr($hide_val, 'data-hide'); ?>
                                                <?php if($selected) echo "checked"; ?> <?php conall_edge_inline_style($hide_radios); ?>
                                                type="radio"
                                                name="<?php echo esc_attr($name);  ?>"
                                                value="<?php echo esc_attr($key); ?>"
                                                <?php if($dependence) conall_edge_class_attribute("dependence"); ?>> <?php if(!empty($atts["label"]) && !$hide_labels) echo esc_attr($atts["label"]); ?>

                                            <?php if($use_images) { ?>
                                                <img title="<?php if(!empty($atts["label"])) echo esc_attr($atts["label"]); ?>" src="<?php echo esc_url($atts['image']); ?>" alt="<?php echo esc_attr("$key image") ?>"/>
                                            <?php } ?>
                                        </label>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- close div.edgtf-section-content -->

        </div>
    <?php
    }
}

class ConallEdgeFieldCheckBoxGroup extends ConallEdgeClassFieldType {

	public function render($name, $label = '', $description = '', $options = array(), $args = array(), $hidden = false) {
		if(!(is_array($options) && count($options))) {
			return;
		}

		$saved_value = conall_edge_option_get_value($name);
		?>
		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="edgtf-checkbox-group-holder">
									<div class="checkbox-inline" style="display: none">
										<label>
											<input checked type="checkbox" value="" name="<?php echo esc_attr($name.'[]'); ?>">
										</label>
									</div>
								<?php foreach($options as $option_key => $option_label) : ?>
									<?php
									$i = 1;
									$checked = is_array($saved_value) && in_array($option_key, $saved_value);
									$checked_attr = $checked ? 'checked' : '';
									?>

									<div class="checkbox-inline">
										<label>
											<input <?php echo esc_attr($checked_attr); ?> type="checkbox" id="<?php echo esc_attr($option_key).'-'.$i; ?>" value="<?php echo esc_attr($option_key); ?>" name="<?php echo esc_attr($name.'[]'); ?>">
											<label for="<?php echo esc_attr($option_key).'-'.$i; ?>"><?php echo esc_html($option_label); ?></label>
										</label>
									</div>
								<?php $i++; endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>

		<?php
	}
}

class ConallEdgeClassFieldCheckBox extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {

		$checked = "";
		if ($default_value == $value)
			$checked = "checked";
		$html = '<input type="checkbox" name="'.$name.'" value="'.$default_value.'" '.$checked.' /> '.$label.'<br />';
		echo wp_kses($html, array(
			'input' => array(
				'type' => true,
				'name' => true,
				'value' => true,
				'checked' => true
			),
			'br' => true
		));
	}
}

class ConallEdgeClassFieldDate extends ConallEdgeClassFieldType {

	public function render( $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {
		$col_width = 2;
		if(isset($args["col_width"]))
			$col_width = $args["col_width"];
		?>

		<div class="edgtf-page-form-section" id="edgtf_<?php echo esc_attr($name); ?>"<?php if ($hidden) { ?> style="display: none"<?php } ?>>


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($label); ?></h4>

				<p><?php echo esc_html($description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-<?php echo esc_attr($col_width); ?>">
							<input type="text"
								   id = "portfolio_date"
								   class="datepicker form-control edgtf-input edgtf-form-element"
								   name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr(conall_edge_option_get_value($name)); ?>"
								/></div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->
		</div>
	<?php
	}
}

class ConallEdgeClassFieldFactory {

	public function render( $field_type, $name, $label="", $description="", $options = array(), $args = array(), $hidden = false ) {


		switch ( strtolower( $field_type ) ) {

			case 'text':
				$field = new ConallEdgeClassFieldText();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'textsimple':
				$field = new ConallEdgeClassFieldTextSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'textarea':
				$field = new ConallEdgeClassFieldTextArea();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'textareasimple':
				$field = new ConallEdgeClassFieldTextAreaSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'color':
				$field = new ConallEdgeClassFieldColor();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'colorsimple':
				$field = new ConallEdgeClassFieldColorSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'image':
				$field = new ConallEdgeClassFieldImage();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

            case 'imagesimple':
				$field = new ConallEdgeClassFieldImageSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'font':
				$field = new ConallEdgeClassFieldFont();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'fontsimple':
				$field = new ConallEdgeClassFieldFontSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'select':
				$field = new ConallEdgeClassFieldSelect();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'selectblank':
				$field = new ConallEdgeClassFieldSelectBlank();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'selectsimple':
				$field = new ConallEdgeClassFieldSelectSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'selectblanksimple':
				$field = new ConallEdgeClassFieldSelectBlankSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'yesno':
				$field = new ConallEdgeClassFieldYesNo();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'yesnosimple':
				$field = new ConallEdgeClassFieldYesNoSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'onoff':
				$field = new ConallEdgeClassFieldOnOff();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'portfoliofollow':
				$field = new ConallEdgeClassFieldPortfolioFollow();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'zeroone':
				$field = new ConallEdgeClassFieldZeroOne();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'imagevideo':
				$field = new ConallEdgeClassFieldImageVideo();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'yesempty':
				$field = new ConallEdgeClassFieldYesEmpty();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'flagpost':
				$field = new ConallEdgeClassFieldFlagPost();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'flagpage':
				$field = new ConallEdgeClassFieldFlagPage();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'flagmedia':
				$field = new ConallEdgeClassFieldFlagMedia();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'flagportfolio':
				$field = new ConallEdgeClassFieldFlagPortfolio();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'flagproduct':
				$field = new ConallEdgeClassFieldFlagProduct();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'range':
				$field = new ConallEdgeClassFieldRange();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'rangesimple':
				$field = new ConallEdgeClassFieldRangeSimple();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'radio':
				$field = new ConallEdgeClassFieldRadio();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'checkboxgroup':
				$field = new ConallEdgeFieldCheckBoxGroup();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'checkbox':
				$field = new ConallEdgeClassFieldCheckBox();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;

			case 'date':
				$field = new ConallEdgeClassFieldDate();
				$field->render( $name, $label, $description, $options, $args, $hidden );
				break;
            case 'radiogroup':
                $field = new ConallEdgeClassFieldRadioGroup();
                $field->render( $name, $label, $description, $options, $args, $hidden );
                break;
			default:
				break;

		}

	}

}

/*
   Class: ConallEdgeClassMultipleImages
   A class that initializes Edge Multiple Images
*/
class ConallEdgeClassMultipleImages implements iConallEdgeInterfaceRender {
	private $name;
	private $label;
	private $description;


	function __construct($name,$label="",$description="") {
		global $conall_edge_framework;
		$this->name = $name;
		$this->label = $label;
		$this->description = $description;
		$conall_edge_framework->edgtMetaBoxes->addOption($this->name,"");
	}

	public function render($factory) {
		global $post;
		?>

		<div class="edgtf-page-form-section">


			<div class="edgtf-field-desc">
				<h4><?php echo esc_html($this->label); ?></h4>

				<p><?php echo esc_html($this->description); ?></p>
			</div>
			<!-- close div.edgtf-field-desc -->

			<div class="edgtf-section-content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<ul class="edgt-gallery-images-holder clearfix">
								<?php
								$image_gallery_val = get_post_meta( $post->ID, $this->name , true );
								if($image_gallery_val!='' ) $image_gallery_array=explode(',',$image_gallery_val);

								if(isset($image_gallery_array) && count($image_gallery_array)!=0):

									foreach($image_gallery_array as $gimg_id):

										$gimage_wp = wp_get_attachment_image_src($gimg_id,'thumbnail', true);
										echo '<li class="edgt-gallery-image-holder"><img src="'.esc_url($gimage_wp[0]).'"/></li>';

									endforeach;

								endif;
								?>
							</ul>
							<input type="hidden" value="<?php echo esc_attr($image_gallery_val); ?>" id="<?php echo esc_attr( $this->name) ?>" name="<?php echo esc_attr( $this->name) ?>">
							<div class="edgtf-gallery-uploader">
								<a class="edgtf-gallery-upload-btn btn btn-sm btn-primary" href="javascript:void(0)"><?php esc_html_e('Upload', 'conall'); ?></a>
								<a class="edgtf-gallery-clear-btn btn btn-sm btn-default pull-right" href="javascript:void(0)"><?php esc_html_e('Remove All', 'conall'); ?></a>
							</div>
							<?php wp_nonce_field( 'edgtf_gallery_upload_get_images_' . esc_attr( $this->name ), 'edgtf_gallery_upload_get_images_' . esc_attr( $this->name ) ); ?>
						</div>
					</div>
				</div>
			</div>
			<!-- close div.edgtf-section-content -->

		</div>
	<?php

	}
}

/*
   Class: ConallEdgeClassImagesVideos
   A class that initializes Edge Images Videos
*/
class ConallEdgeClassImagesVideos implements iConallEdgeInterfaceRender {
	private $label;
	private $description;


	function __construct($label="",$description="") {
		$this->label = $label;
		$this->description = $description;
	}

	public function render($factory) {
		global $post;
		?>
		<div class="edgtf_hidden_portfolio_images" style="display: none">
			<div class="edgtf-page-form-section">


				<div class="edgtf-field-desc">
					<h4><?php echo esc_html($this->label); ?></h4>

					<p><?php echo esc_html($this->description); ?></p>
				</div>
				<!-- close div.edgtf-field-desc -->

				<div class="edgtf-section-content">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-2">
								<em class="edgtf-field-description"><?php esc_html_e( 'Order Number', 'conall' ); ?></em>
								<input type="text"
									   class="form-control edgtf-input edgtf-form-element"
									   id="portfolioimgordernumber_x"
									   name="portfolioimgordernumber_x"
									   /></div>
							<div class="col-lg-10">
								<em class="edgtf-field-description"><?php esc_html_e( 'Image/Video title (only for gallery layout - Portfolio Style 6)', 'conall' ); ?></em>
								<input type="text"
									   class="form-control edgtf-input edgtf-form-element"
									   id="portfoliotitle_x"
									   name="portfoliotitle_x"
									   /></div>
						</div>
						<div class="row next-row">
							<div class="col-lg-12">
								<em class="edgtf-field-description"><?php esc_html_e( 'Image', 'conall' ); ?></em>
								<div class="edgtf-media-uploader">
									<div style="display: none"
										 class="edgtf-media-image-holder">
										<img src="" class="edgtf-media-image img-thumbnail"/>
									</div>
									<div style="display: none"
										 class="edgtf-media-meta-fields">
										<input type="hidden" class="edgtf-media-upload-url"
											   name="portfolioimg_x"
											   id="portfolioimg_x"/>
										<input type="hidden"
											   class="edgtf-media-upload-height"
											   name="edgt_options_theme[media-upload][height]"
											   value=""/>
										<input type="hidden"
											   class="edgtf-media-upload-width"
											   name="edgt_options_theme[media-upload][width]"
											   value=""/>
									</div>
									<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
									   href="javascript:void(0)"
									   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
									   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
									<a style="display: none;" href="javascript: void(0)"
									   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
								</div>
							</div>
						</div>
						<div class="row next-row">
							<div class="col-lg-3">
								<em class="edgtf-field-description"><?php esc_html_e( 'Video type', 'conall' ); ?></em>
								<select class="form-control edgtf-form-element edgtf-portfoliovideotype"
										name="portfoliovideotype_x" id="portfoliovideotype_x">
									<option value=""></option>
									<option value="youtube"><?php esc_html_e( 'Youtube', 'conall' ); ?></option>
									<option value="vimeo"><?php esc_html_e( 'Vimeo', 'conall' ); ?></option>
									<option value="self"><?php esc_html_e( 'Self hosted', 'conall' ); ?></option>
								</select>
							</div>
							<div class="col-lg-3">
								<em class="edgtf-field-description"><?php esc_html_e( 'Video ID', 'conall' ); ?></em>
								<input type="text"
									   class="form-control edgtf-input edgtf-form-element"
									   id="portfoliovideoid_x"
									   name="portfoliovideoid_x"
									   /></div>
						</div>
						<div class="row next-row">
							<div class="col-lg-12">
								<em class="edgtf-field-description"><?php esc_html_e( 'Video image', 'conall' ); ?></em>
								<div class="edgtf-media-uploader">
									<div style="display: none"
										 class="edgtf-media-image-holder">
										<img src="" class="edgtf-media-image img-thumbnail"/>
									</div>
									<div style="display: none"
										 class="edgtf-media-meta-fields">
										<input type="hidden" class="edgtf-media-upload-url"
											   name="portfoliovideoimage_x"
											   id="portfoliovideoimage_x"/>
										<input type="hidden"
											   class="edgtf-media-upload-height"
											   name="edgt_options_theme[media-upload][height]"
											   value=""/>
										<input type="hidden"
											   class="edgtf-media-upload-width"
											   name="edgt_options_theme[media-upload][width]"
											   value=""/>
									</div>
									<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
									   href="javascript:void(0)"
									   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
									   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
									<a style="display: none;" href="javascript: void(0)"
									   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
								</div>
							</div>
						</div>
						<div class="row next-row">
							<div class="col-lg-4">
								<em class="edgtf-field-description"><?php esc_html_e( 'Video webm', 'conall' ); ?></em>
								<input type="text"
									   class="form-control edgtf-input edgtf-form-element"
									   id="portfoliovideowebm_x"
									   name="portfoliovideowebm_x"
									   /></div>
							<div class="col-lg-4">
								<em class="edgtf-field-description"><?php esc_html_e( 'Video mp4', 'conall' ); ?></em>
								<input type="text"
									   class="form-control edgtf-input edgtf-form-element"
									   id="portfoliovideomp4_x"
									   name="portfoliovideomp4_x"
									   /></div>
							<div class="col-lg-4">
								<em class="edgtf-field-description"><?php esc_html_e( 'Video ogv', 'conall' ); ?></em>
								<input type="text"
									   class="form-control edgtf-input edgtf-form-element"
									   id="portfoliovideoogv_x"
									   name="portfoliovideoogv_x"
									   /></div>
						</div>
						<div class="row next-row">
							<div class="col-lg-12">
								<a class="edgtf_remove_image btn btn-sm btn-primary" href="/" onclick="javascript: return false;"><?php esc_html_e( 'Remove portfolio image/video', 'conall' ); ?></a>
							</div>
						</div>



					</div>
				</div>
				<!-- close div.edgtf-section-content -->

			</div>
		</div>

		<?php
		$no = 1;
		$portfolio_images = get_post_meta( $post->ID, 'edgt_portfolio_images', true );
		if (!empty($portfolio_images) && count($portfolio_images)>1) {
			usort($portfolio_images, "conall_edge_compare_portfolio_videos");
		}
		while (isset($portfolio_images[$no-1])) {
			$portfolio_image = $portfolio_images[$no-1];
			?>
			<div class="edgtf_portfolio_image" rel="<?php echo esc_attr($no); ?>" style="display: block;">

				<div class="edgtf-page-form-section">


					<div class="edgtf-field-desc">
						<h4><?php echo esc_html($this->label); ?></h4>

						<p><?php echo esc_html($this->description); ?></p>
					</div>
					<!-- close div.edgtf-field-desc -->

					<div class="edgtf-section-content">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-2">
									<em class="edgtf-field-description"><?php esc_html_e( 'Order Number', 'conall' ); ?></em>
									<input type="text"
										   class="form-control edgtf-input edgtf-form-element"
										   id="portfolioimgordernumber_<?php echo esc_attr($no); ?>"
										   name="portfolioimgordernumber[]" value="<?php echo isset($portfolio_image['portfolioimgordernumber']) ? esc_attr(stripslashes($portfolio_image['portfolioimgordernumber'])) : ""; ?>"
										   /></div>
								<div class="col-lg-10">
									<em class="edgtf-field-description"><?php esc_html_e( 'Image/Video title (only for gallery layout - Portfolio Style 6)', 'conall' ); ?></em>
									<input type="text"
										   class="form-control edgtf-input edgtf-form-element"
										   id="portfoliotitle_<?php echo esc_attr($no); ?>"
										   name="portfoliotitle[]" value="<?php echo isset($portfolio_image['portfoliotitle']) ? esc_attr(stripslashes($portfolio_image['portfoliotitle'])) : ""; ?>"
										   /></div>
							</div>
							<div class="row next-row">
								<div class="col-lg-12">
									<em class="edgtf-field-description"><?php esc_html_e( 'Image', 'conall' ); ?></em>
									<div class="edgtf-media-uploader">
										<div<?php if (stripslashes($portfolio_image['portfolioimg']) == false) { ?> style="display: none"<?php } ?> class="edgtf-media-image-holder">
											<img src="<?php if ( stripslashes($portfolio_image['portfolioimg']) == true ) { echo esc_url( conall_edge_get_attachment_thumb_url( esc_attr($portfolio_image['portfolioimg']) ) ); } ?>" alt="<?php esc_attr_e( 'Image thumbnail', 'conall' ); ?>" class="edgtf-media-image img-thumbnail"/>
										</div>
										<div style="display: none"
											 class="edgtf-media-meta-fields">
											<input type="hidden" class="edgtf-media-upload-url"
												   name="portfolioimg[]"
												   id="portfolioimg_<?php echo esc_attr($no); ?>"
												   value="<?php echo stripslashes($portfolio_image['portfolioimg']); ?>"/>
											<input type="hidden"
												   class="edgtf-media-upload-height"
												   name="edgt_options_theme[media-upload][height]"
												   value=""/>
											<input type="hidden"
												   class="edgtf-media-upload-width"
												   name="edgt_options_theme[media-upload][width]"
												   value=""/>
										</div>
										<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
										   href="javascript:void(0)"
										   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
										   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
										<a style="display: none;" href="javascript: void(0)"
										   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
									</div>
								</div>
							</div>
							<div class="row next-row">
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Video type', 'conall' ); ?></em>
									<select class="form-control edgtf-form-element edgtf-portfoliovideotype"
											name="portfoliovideotype[]" id="portfoliovideotype_<?php echo esc_attr($no); ?>">
										<option value=""></option>
										<option <?php if ($portfolio_image['portfoliovideotype'] == "youtube") { echo "selected='selected'"; } ?>  value="youtube"><?php esc_html_e( 'Youtube', 'conall' ); ?></option>
										<option <?php if ($portfolio_image['portfoliovideotype'] == "vimeo") { echo "selected='selected'"; } ?>  value="vimeo"><?php esc_html_e( 'Vimeo', 'conall' ); ?></option>
										<option <?php if ($portfolio_image['portfoliovideotype'] == "self") { echo "selected='selected'"; } ?>  value="self"><?php esc_html_e( 'Self hosted', 'conall' ); ?></option>
									</select>
								</div>
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Video ID', 'conall' ); ?></em>
									<input type="text"
										   class="form-control edgtf-input edgtf-form-element"
										   id="portfoliovideoid_<?php echo esc_attr($no); ?>"
										   name="portfoliovideoid[]" value="<?php echo isset($portfolio_image['portfoliovideoid']) ? esc_attr(stripslashes($portfolio_image['portfoliovideoid'])) : ""; ?>"
										   /></div>
							</div>
							<div class="row next-row">
								<div class="col-lg-12">
									<em class="edgtf-field-description"><?php esc_html_e( 'Video image', 'conall' ); ?></em>
									<div class="edgtf-media-uploader">
										<div<?php if (stripslashes($portfolio_image['portfoliovideoimage']) == false) { ?> style="display: none"<?php } ?>
											class="edgtf-media-image-holder">
											<img src="<?php if ( stripslashes($portfolio_image['portfoliovideoimage']) == true ) { echo esc_url( conall_edge_get_attachment_thumb_url( esc_attr($portfolio_image['portfoliovideoimage']) ) ); } ?>" alt="<?php esc_attr_e( 'Image thumbnail', 'conall' ); ?>" class="edgtf-media-image img-thumbnail"/>
										</div>
										<div style="display: none"
											 class="edgtf-media-meta-fields">
											<input type="hidden" class="edgtf-media-upload-url"
												   name="portfoliovideoimage[]"
												   id="portfoliovideoimage_<?php echo esc_attr($no); ?>"
												   value="<?php echo stripslashes($portfolio_image['portfoliovideoimage']); ?>"/>
											<input type="hidden"
												   class="edgtf-media-upload-height"
												   name="edgt_options_theme[media-upload][height]"
												   value=""/>
											<input type="hidden"
												   class="edgtf-media-upload-width"
												   name="edgt_options_theme[media-upload][width]"
												   value=""/>
										</div>
										<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
										   href="javascript:void(0)"
										   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
										   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
										<a style="display: none;" href="javascript: void(0)"
										   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
									</div>
								</div>
							</div>
							<div class="row next-row">
								<div class="col-lg-4">
									<em class="edgtf-field-description"><?php esc_html_e( 'Video webm', 'conall' ); ?></em>
									<input type="text"
										   class="form-control edgtf-input edgtf-form-element"
										   id="portfoliovideowebm_<?php echo esc_attr($no); ?>"
										   name="portfoliovideowebm[]" value="<?php echo isset($portfolio_image['portfoliovideowebm']) ? esc_attr(stripslashes($portfolio_image['portfoliovideowebm'])) : ""; ?>"
										   /></div>
								<div class="col-lg-4">
									<em class="edgtf-field-description"><?php esc_html_e( 'Video mp4', 'conall' ); ?></em>
									<input type="text"
										   class="form-control edgtf-input edgtf-form-element"
										   id="portfoliovideomp4_<?php echo esc_attr($no); ?>"
										   name="portfoliovideomp4[]" value="<?php echo isset($portfolio_image['portfoliovideomp4']) ? esc_attr(stripslashes($portfolio_image['portfoliovideomp4'])) : ""; ?>"
										   /></div>
								<div class="col-lg-4">
									<em class="edgtf-field-description"><?php esc_html_e( 'Video ogv', 'conall' ); ?></em>
									<input type="text"
										   class="form-control edgtf-input edgtf-form-element"
										   id="portfoliovideoogv_<?php echo esc_attr($no); ?>"
										   name="portfoliovideoogv[]" value="<?php echo isset($portfolio_image['portfoliovideoogv']) ? esc_attr(stripslashes($portfolio_image['portfoliovideoogv'])):""; ?>"
										   /></div>
							</div>
							<div class="row next-row">
								<div class="col-lg-12">
									<a class="edgtf_remove_image btn btn-sm btn-primary" href="/" onclick="javascript: return false;"><?php esc_html_e( 'Remove portfolio image/video', 'conall' ); ?></a>
								</div>
							</div>



						</div>
					</div>
					<!-- close div.edgtf-section-content -->

				</div>
			</div>
			<?php
			$no++;
		}
		?>
		<br />
		<a class="edgtf_add_image btn btn-sm btn-primary" onclick="javascript: return false;" href="/" ><?php esc_html_e( 'Add portfolio image/video', 'conall' ); ?></a>
	<?php

	}
}

/*
   Class: ConallEdgeClassImagesVideos
   A class that initializes Edge Images Videos
*/
class ConallEdgeClassImagesVideosFramework implements iConallEdgeInterfaceRender {
	private $label;
	private $description;


	function __construct($label="",$description="") {
		$this->label = $label;
		$this->description = $description;
	}

	public function render($factory) {
		global $post;
		?>

		<!--Image hidden start-->
		<div class="edgtf-hidden-portfolio-images"  style="display: none">
			<div class="edgtf-portfolio-toggle-holder">
				<div class="edgtf-portfolio-toggle edgtf-toggle-desc">
					<span class="number">1</span><span class="edgtf-toggle-inner"><?php esc_html_e( 'Image - ', 'conall' ); ?><em><?php esc_html_e( '(Order Number, Image Title)', 'conall' ); ?></em></span>
				</div>
				<div class="edgtf-portfolio-toggle edgtf-portfolio-control">
					<span class="toggle-portfolio-media"><i class="fa fa-caret-up"></i></span>
					<a href="#" class="remove-portfolio-media"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="edgtf-portfolio-toggle-content">
				<div class="edgtf-page-form-section">
					<div class="edgtf-section-content">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-2">
									<div class="edgtf-media-uploader">
										<em class="edgtf-field-description"><?php esc_html_e( 'Image ', 'conall' ); ?></em>
										<div style="display: none" class="edgtf-media-image-holder">
											<img src="" class="edgtf-media-image img-thumbnail">
										</div>
										<div  class="edgtf-media-meta-fields">
											<input type="hidden" class="edgtf-media-upload-url" name="portfolioimg_x" id="portfolioimg_x">
											<input type="hidden" class="edgtf-media-upload-height" name="edgt_options_theme[media-upload][height]" value="">
											<input type="hidden" class="edgtf-media-upload-width" name="edgt_options_theme[media-upload][width]" value="">
										</div>
										<a class="edgtf-media-upload-btn btn btn-sm btn-primary" href="javascript:void(0)" data-frame-title="<?php esc_attr_e( 'Select Image', 'conall' ); ?>" data-frame-button-text="<?php esc_attr_e( 'Select Image', 'conall' ); ?>"><?php esc_html_e( 'Upload', 'conall' ); ?></a>
										<a style="display: none;" href="javascript: void(0)" class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e( 'Remove', 'conall' ); ?></a>
									</div>
								</div>
								<div class="col-lg-2">
									<em class="edgtf-field-description"><?php esc_html_e( 'Order Number', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfolioimgordernumber_x" name="portfolioimgordernumber_x" >
								</div>
								<div class="col-lg-8">
									<em class="edgtf-field-description"><?php esc_html_e( 'Image Title (works only for Gallery portfolio type selected) ', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfoliotitle_x" name="portfoliotitle_x" >
								</div>
							</div>
							<input type="hidden" name="portfoliovideoimage_x" id="portfoliovideoimage_x">
							<input type="hidden" name="portfoliovideotype_x" id="portfoliovideotype_x">
							<input type="hidden" name="portfoliovideoid_x" id="portfoliovideoid_x">
							<input type="hidden" name="portfoliovideowebm_x" id="portfoliovideowebm_x">
							<input type="hidden" name="portfoliovideomp4_x" id="portfoliovideomp4_x">
							<input type="hidden" name="portfoliovideoogv_x" id="portfoliovideoogv_x">
							<input type="hidden" name="portfolioimgtype_x" id="portfolioimgtype_x" value="image">

						</div><!-- close div.container-fluid -->
					</div><!-- close div.edgtf-section-content -->
				</div>
			</div>
		</div>
		<!--Image hidden End-->

		<!--Video Hidden Start-->
		<div class="edgtf-hidden-portfolio-videos"  style="display: none">
			<div class="edgtf-portfolio-toggle-holder">
				<div class="edgtf-portfolio-toggle edgtf-toggle-desc">
					<span class="number">2</span><span class="edgtf-toggle-inner"><?php esc_html_e( 'Video - ', 'conall' ); ?><em><?php esc_html_e( '(Order Number, Video Title)', 'conall' ); ?></em></span>
				</div>
				<div class="edgtf-portfolio-toggle edgtf-portfolio-control">
					<span class="toggle-portfolio-media"><i class="fa fa-caret-up"></i></span> <a href="#" class="remove-portfolio-media"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="edgtf-portfolio-toggle-content">
				<div class="edgtf-page-form-section">
					<div class="edgtf-section-content">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-2">
									<div class="edgtf-media-uploader">
										<em class="edgtf-field-description"><?php esc_html_e( 'Cover Video Image ', 'conall' ); ?></em>
										<div style="display: none" class="edgtf-media-image-holder">
											<img src="" class="edgtf-media-image img-thumbnail">
										</div>
										<div style="display: none" class="edgtf-media-meta-fields">
											<input type="hidden" class="edgtf-media-upload-url" name="portfoliovideoimage_x" id="portfoliovideoimage_x">
											<input type="hidden" class="edgtf-media-upload-height" name="edgt_options_theme[media-upload][height]" value="">
											<input type="hidden" class="edgtf-media-upload-width" name="edgt_options_theme[media-upload][width]" value="">
										</div>
										<a class="edgtf-media-upload-btn btn btn-sm btn-primary" href="javascript:void(0)" data-frame-title="<?php esc_attr_e( 'Select Image', 'conall' ); ?>" data-frame-button-text="<?php esc_attr_e( 'Select Image', 'conall' ); ?>"><?php esc_html_e( 'Upload', 'conall' ); ?></a>
										<a style="display: none;" href="javascript: void(0)" class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e( 'Remove', 'conall' ); ?></a>
									</div>
								</div>
								<div class="col-lg-10">
									<div class="row">
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Order Number', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfolioimgordernumber_x" name="portfolioimgordernumber_x" >
										</div>
										<div class="col-lg-10">
											<em class="edgtf-field-description"><?php esc_html_e( 'Video Title (works only for Gallery portfolio type selected)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfoliotitle_x" name="portfoliotitle_x" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Video type', 'conall' ); ?></em>
											<select class="form-control edgtf-form-element edgtf-portfoliovideotype" name="portfoliovideotype_x" id="portfoliovideotype_x">
												<option value=""></option>
												<option value="youtube"><?php esc_html_e( 'Youtube', 'conall' ); ?></option>
												<option value="vimeo"><?php esc_html_e( 'Vimeo', 'conall' ); ?></option>
												<option value="self"><?php esc_html_e( 'Self hosted', 'conall' ); ?></option>
											</select>
										</div>
										<div class="col-lg-2 edgtf-video-id-holder">
											<em class="edgtf-field-description" id="videoId"><?php esc_html_e( 'Video ID', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfoliovideoid_x" name="portfoliovideoid_x" >
										</div>
									</div>

									<div class="row next-row edgtf-video-self-hosted-path-holder">
										<div class="col-lg-4">
											<em class="edgtf-field-description"><?php esc_html_e( 'Video webm', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfoliovideowebm_x" name="portfoliovideowebm_x" >
										</div>
										<div class="col-lg-4">
											<em class="edgtf-field-description"><?php esc_html_e( 'Video mp4', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfoliovideomp4_x" name="portfoliovideomp4_x" >
										</div>
										<div class="col-lg-4">
											<em class="edgtf-field-description"><?php esc_html_e( 'Video ogv', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfoliovideoogv_x" name="portfoliovideoogv_x" >
										</div>
									</div>
								</div>

							</div>
							<input type="hidden" name="portfolioimg_x" id="portfolioimg_x">
							<input type="hidden" name="portfolioimgtype_x" id="portfolioimgtype_x" value="video">
						</div><!-- close div.container-fluid -->
					</div><!-- close div.edgtf-section-content -->
				</div>
			</div>
		</div>
		<!--Video Hidden End-->


		<?php
		$no = 1;
		$portfolio_images = get_post_meta( $post->ID, 'edgt_portfolio_images', true );
		if (!empty($portfolio_images) && count($portfolio_images)>1) {
			usort($portfolio_images, "conall_edge_compare_portfolio_videos");
		}
		while (isset($portfolio_images[$no-1])) {
			$portfolio_image = $portfolio_images[$no-1];
			if (isset($portfolio_image['portfolioimgtype']))
				$portfolio_img_type = $portfolio_image['portfolioimgtype'];
			else {
				if (stripslashes($portfolio_image['portfolioimg']) == true)
					$portfolio_img_type = "image";
				else
					$portfolio_img_type = "video";
			}
			if ($portfolio_img_type == "image") {
				?>

				<div class="edgtf-portfolio-images edgtf-portfolio-media" rel="<?php echo esc_attr($no); ?>">
					<div class="edgtf-portfolio-toggle-holder">
						<div class="edgtf-portfolio-toggle edgtf-toggle-desc">
							<span class="number"><?php echo esc_html($no); ?></span><span class="edgtf-toggle-inner"><?php esc_html_e( 'Image - ', 'conall' ); ?><em>(<?php echo stripslashes($portfolio_image['portfolioimgordernumber']); ?>, <?php echo stripslashes($portfolio_image['portfoliotitle']); ?>)</em></span>
						</div>
						<div class="edgtf-portfolio-toggle edgtf-portfolio-control">
							<a href="#" class="toggle-portfolio-media"><i class="fa fa-caret-down"></i></a>
							<a href="#" class="remove-portfolio-media"><i class="fa fa-times"></i></a>
						</div>
					</div>
					<div class="edgtf-portfolio-toggle-content" style="display: none">
						<div class="edgtf-page-form-section">
							<div class="edgtf-section-content">
								<div class="container-fluid">
									<div class="row">
										<div class="col-lg-2">
											<div class="edgtf-media-uploader">
												<em class="edgtf-field-description"><?php esc_html_e( 'Image ', 'conall' ); ?></em>
												<div<?php if (stripslashes($portfolio_image['portfolioimg']) == false) { ?> style="display: none"<?php } ?>
													class="edgtf-media-image-holder">
													<img src="<?php if ( stripslashes($portfolio_image['portfolioimg']) == true ) { echo esc_url( conall_edge_get_attachment_thumb_url( esc_attr($portfolio_image['portfolioimg']) ) ); } ?>" alt="<?php esc_attr_e( 'Image thumbnail', 'conall' ); ?>" class="edgtf-media-image img-thumbnail"/>
												</div>
												<div style="display: none"
													 class="edgtf-media-meta-fields">
													<input type="hidden" class="edgtf-media-upload-url"
														   name="portfolioimg[]"
														   id="portfolioimg_<?php echo esc_attr($no); ?>"
														   value="<?php echo stripslashes($portfolio_image['portfolioimg']); ?>"/>
													<input type="hidden"
														   class="edgtf-media-upload-height"
														   name="edgt_options_theme[media-upload][height]"
														   value=""/>
													<input type="hidden"
														   class="edgtf-media-upload-width"
														   name="edgt_options_theme[media-upload][width]"
														   value=""/>
												</div>
												<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
												   href="javascript:void(0)"
												   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
												   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
												<a style="display: none;" href="javascript: void(0)"
												   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
											</div>
										</div>
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Order Number', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfolioimgordernumber_<?php echo esc_attr($no); ?>" name="portfolioimgordernumber[]" value="<?php echo isset($portfolio_image['portfolioimgordernumber']) ? esc_attr(stripslashes($portfolio_image['portfolioimgordernumber'])) : ""; ?>" >
										</div>
										<div class="col-lg-8">
											<em class="edgtf-field-description"><?php esc_html_e( 'Image Title (works only for Gallery portfolio type selected) ', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfoliotitle_<?php echo esc_attr($no); ?>" name="portfoliotitle[]" value="<?php echo isset($portfolio_image['portfoliotitle']) ? esc_attr(stripslashes($portfolio_image['portfoliotitle'])) : ""; ?>" >
										</div>
									</div>
									<input type="hidden" id="portfoliovideoimage_<?php echo esc_attr($no); ?>" name="portfoliovideoimage[]">
									<input type="hidden" id="portfoliovideotype_<?php echo esc_attr($no); ?>" name="portfoliovideotype[]">
									<input type="hidden" id="portfoliovideoid_<?php echo esc_attr($no); ?>" name="portfoliovideoid[]">
									<input type="hidden" id="portfoliovideowebm_<?php echo esc_attr($no); ?>" name="portfoliovideowebm[]">
									<input type="hidden" id="portfoliovideomp4_<?php echo esc_attr($no); ?>" name="portfoliovideomp4[]">
									<input type="hidden" id="portfoliovideoogv_<?php echo esc_attr($no); ?>" name="portfoliovideoogv[]">
									<input type="hidden" id="portfolioimgtype_<?php echo esc_attr($no); ?>" name="portfolioimgtype[]" value="image">
								</div><!-- close div.container-fluid -->
							</div><!-- close div.edgtf-section-content -->
						</div>
					</div>
				</div>

			<?php
			} else {
				?>
				<div class="edgtf-portfolio-videos edgtf-portfolio-media" rel="<?php echo esc_attr($no); ?>">
					<div class="edgtf-portfolio-toggle-holder">
						<div class="edgtf-portfolio-toggle edgtf-toggle-desc">
							<span class="number"><?php echo esc_html($no); ?></span><span class="edgtf-toggle-inner"><?php esc_html_e( 'Video - ', 'conall' ); ?><em>(<?php echo stripslashes($portfolio_image['portfolioimgordernumber']); ?>, <?php echo stripslashes($portfolio_image['portfoliotitle']); ?></em>) </span>
						</div>
						<div class="edgtf-portfolio-toggle edgtf-portfolio-control">
							<a href="#" class="toggle-portfolio-media"><i class="fa fa-caret-down"></i></a> <a href="#" class="remove-portfolio-media"><i class="fa fa-times"></i></a>
						</div>
					</div>
					<div class="edgtf-portfolio-toggle-content" style="display: none">
						<div class="edgtf-page-form-section">
							<div class="edgtf-section-content">
								<div class="container-fluid">
									<div class="row">
										<div class="col-lg-2">
											<div class="edgtf-media-uploader">
												<em class="edgtf-field-description"><?php esc_html_e( 'Cover Video Image ', 'conall' ); ?></em>
												<div<?php if (stripslashes($portfolio_image['portfoliovideoimage']) == false) { ?> style="display: none"<?php } ?>
													class="edgtf-media-image-holder">
													<img src="<?php if ( stripslashes($portfolio_image['portfoliovideoimage']) == true ) { echo esc_url( conall_edge_get_attachment_thumb_url( esc_attr($portfolio_image['portfoliovideoimage']) ) ); } ?>" alt="<?php esc_attr_e( 'Image thumbnail', 'conall' ); ?>" class="edgtf-media-image img-thumbnail"/>
												</div>
												<div style="display: none"
													 class="edgtf-media-meta-fields">
													<input type="hidden" class="edgtf-media-upload-url"
														   name="portfoliovideoimage[]"
														   id="portfoliovideoimage_<?php echo esc_attr($no); ?>"
														   value="<?php echo stripslashes($portfolio_image['portfoliovideoimage']); ?>"/>
													<input type="hidden"
														   class="edgtf-media-upload-height"
														   name="edgt_options_theme[media-upload][height]"
														   value=""/>
													<input type="hidden"
														   class="edgtf-media-upload-width"
														   name="edgt_options_theme[media-upload][width]"
														   value=""/>
												</div>
												<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
												   href="javascript:void(0)"
												   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
												   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
												<a style="display: none;" href="javascript: void(0)"
												   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
											</div>
										</div>
										<div class="col-lg-10">
											<div class="row">
												<div class="col-lg-2">
													<em class="edgtf-field-description"><?php esc_html_e( 'Order Number', 'conall' ); ?></em>
													<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfolioimgordernumber_<?php echo esc_attr($no); ?>" name="portfolioimgordernumber[]" value="<?php echo isset($portfolio_image['portfolioimgordernumber']) ? esc_attr(stripslashes($portfolio_image['portfolioimgordernumber'])) : ""; ?>" >
												</div>
												<div class="col-lg-10">
													<em class="edgtf-field-description"><?php esc_html_e( 'Video Title (works only for Gallery portfolio type selected) ', 'conall' ); ?></em>
													<input type="text" class="form-control edgtf-input edgtf-form-element" id="portfoliotitle_<?php echo esc_attr($no); ?>" name="portfoliotitle[]" value="<?php echo isset($portfolio_image['portfoliotitle']) ? esc_attr(stripslashes($portfolio_image['portfoliotitle'])) : ""; ?>" >
												</div>
											</div>
											<div class="row next-row">
												<div class="col-lg-2">
													<em class="edgtf-field-description"><?php esc_html_e( 'Video Type', 'conall' ); ?></em>
													<select class="form-control edgtf-form-element edgtf-portfoliovideotype"
															name="portfoliovideotype[]" id="portfoliovideotype_<?php echo esc_attr($no); ?>">
														<option value=""></option>
														<option <?php if ($portfolio_image['portfoliovideotype'] == "youtube") { echo "selected='selected'"; } ?>  value="youtube"><?php esc_html_e( 'Youtube', 'conall' ); ?></option>
														<option <?php if ($portfolio_image['portfoliovideotype'] == "vimeo") { echo "selected='selected'"; } ?>  value="vimeo"><?php esc_html_e( 'Vimeo', 'conall' ); ?></option>
														<option <?php if ($portfolio_image['portfoliovideotype'] == "self") { echo "selected='selected'"; } ?>  value="self"><?php esc_html_e( 'Self hosted', 'conall' ); ?></option>
													</select>
												</div>
												<div class="col-lg-2 edgtf-video-id-holder">
													<em class="edgtf-field-description"><?php esc_html_e( 'Video ID', 'conall' ); ?></em>
													<input type="text"
														   class="form-control edgtf-input edgtf-form-element"
														   id="portfoliovideoid_<?php echo esc_attr($no); ?>"
														   name="portfoliovideoid[]" value="<?php echo isset($portfolio_image['portfoliovideoid']) ? esc_attr(stripslashes($portfolio_image['portfoliovideoid'])) : ""; ?>"
														   />
												</div>
											</div>

											<div class="row next-row edgtf-video-self-hosted-path-holder">
												<div class="col-lg-4">
													<em class="edgtf-field-description"><?php esc_html_e( 'Video webm', 'conall' ); ?></em>
													<input type="text"
														   class="form-control edgtf-input edgtf-form-element"
														   id="portfoliovideowebm_<?php echo esc_attr($no); ?>"
														   name="portfoliovideowebm[]" value="<?php echo isset($portfolio_image['portfoliovideowebm'])? esc_attr(stripslashes($portfolio_image['portfoliovideowebm'])) : ""; ?>"
														   /></div>
												<div class="col-lg-4">
													<em class="edgtf-field-description"><?php esc_html_e( 'Video mp4', 'conall' ); ?></em>
													<input type="text"
														   class="form-control edgtf-input edgtf-form-element"
														   id="portfoliovideomp4_<?php echo esc_attr($no); ?>"
														   name="portfoliovideomp4[]" value="<?php echo isset($portfolio_image['portfoliovideomp4']) ? esc_attr(stripslashes($portfolio_image['portfoliovideomp4'])) : ""; ?>"
														   /></div>
												<div class="col-lg-4">
													<em class="edgtf-field-description"><?php esc_html_e( 'Video ogv', 'conall' ); ?></em>
													<input type="text"
														   class="form-control edgtf-input edgtf-form-element"
														   id="portfoliovideoogv_<?php echo esc_attr($no); ?>"
														   name="portfoliovideoogv[]" value="<?php echo isset($portfolio_image['portfoliovideoogv']) ? esc_attr(stripslashes($portfolio_image['portfoliovideoogv'])) : ""; ?>"
														   /></div>
											</div>
										</div>

									</div>
									<input type="hidden" id="portfolioimg_<?php echo esc_attr($no); ?>" name="portfolioimg[]">
									<input type="hidden" id="portfolioimgtype_<?php echo esc_attr($no); ?>" name="portfolioimgtype[]" value="video">
								</div><!-- close div.container-fluid -->
							</div><!-- close div.edgtf-section-content -->
						</div>
					</div>
				</div>
			<?php
			}
			$no++;
		}
		?>

		<div class="edgtf-portfolio-add">
			<a class="edgtf-add-image btn btn-sm btn-primary" href="#"><i class="fa fa-camera"></i><?php esc_html_e( ' Add Image', 'conall' ); ?></a>
			<a class="edgtf-add-video btn btn-sm btn-primary" href="#"><i class="fa fa-video-camera"></i><?php esc_html_e( ' Add Video', 'conall' ); ?></a>

			<a class="edgtf-toggle-all-media btn btn-sm btn-default pull-right" href="#"><?php esc_html_e( ' Expand All', 'conall' ); ?></a>
		</div>
	<?php

	}
}

class ConallEdgeClassTwitterFramework implements  iConallEdgeInterfaceRender {
    public function render($factory) {
        $twitterApi = EdgefTwitterApi::getInstance();
        $message = '';

        if(!empty($_GET['oauth_token']) && !empty($_GET['oauth_verifier'])) {
            if(!empty($_GET['oauth_token'])) {
                update_option($twitterApi::AUTHORIZE_TOKEN_FIELD, $_GET['oauth_token']);
            }

            if(!empty($_GET['oauth_verifier'])) {
                update_option($twitterApi::AUTHORIZE_VERIFIER_FIELD, $_GET['oauth_verifier']);
            }

            $responseObj = $twitterApi->obtainAccessToken();
            if($responseObj->status) {
                $message = esc_html__('You have successfully connected with your Twitter account. If you have any issues fetching data from Twitter try reconnecting.', 'conall');
            } else {
                $message = $responseObj->message;
            }
        }

        $buttonText = $twitterApi->hasUserConnected() ? esc_html__('Re-connect with Twitter', 'conall') : esc_html__('Connect with Twitter', 'conall');
    ?>
        <?php if($message !== '') { ?>
            <div class="alert alert-success" style="margin-top: 20px;">
                <span><?php echo esc_html($message); ?></span>
            </div>
        <?php } ?>
        <div class="edgtf-page-form-section" id="edgtf_enable_social_share">

            <div class="edgtf-field-desc">
                <h4><?php esc_html_e('Connect with Twitter', 'conall'); ?></h4>

                <p><?php esc_html_e('Connecting with Twitter will enable you to show your latest tweets on your site', 'conall'); ?></p>
            </div>
            <!-- close div.edgtf-field-desc -->

            <div class="edgtf-section-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <a id="edgtf-tw-request-token-btn" class="btn btn-primary" href="#"><?php echo esc_html($buttonText); ?></a>
                            <input type="hidden" data-name="current-page-url" value="<?php echo esc_url($twitterApi->buildCurrentPageURI()); ?>"/>
	                        <?php wp_nonce_field( 'edgtf_twitter_connect_nonce', 'edgtf_twitter_connect_nonce' ); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- close div.edgtf-section-content -->

        </div>
    <?php }
}

class ConallEdgeClassInstagramFramework implements  iConallEdgeInterfaceRender {
	public function render( $factory ) {
		$instagram_api = EdgefInstagramApi::getInstance();
		$message       = '';

		//check if code parameter and instagram parameter is set in URL
		if ( ! empty( $_GET['code'] ) && ! empty( $_GET['instagram'] ) ) {
			//update code option so we can use it later
			$instagram_api->setConnectionType( 'instagram' );
			$instagram_api->instagramStoreCode();
			$instagram_api->instagramExchangeCodeForToken();
			$message = esc_html__( 'You have successfully connected with your Instagram Personal account.', 'themenametd' );
		}

		//check if code parameter and instagram parameter is set in URL
		if ( ! empty( $_GET['access_token'] ) && ! empty( $_GET['facebook'] ) ) {
			//update code option so we can use it later
			$instagram_api->setConnectionType( 'facebook' );
			$instagram_api->facebookStoreToken();
			$message = esc_html__( 'You have successfully connected with your Instagram Business account.', 'themenametd' );
		}

		//check if code parameter and instagram parameter is set in URL
		if ( ! empty( $_GET['disconnect'] ) ) {
			//update code option so we can use it later
			$instagram_api->disconnect();
			$message = esc_html__( 'You have have been disconnected from all Instagram accounts.', 'themenametd' );

		}
		?>

		<?php if ( $message !== '' ) { ?>
			<div class="alert alert-success">
				<span><?php echo esc_html( $message ); ?></span>
			</div>
		<?php } ?>
		<div class="edgtf-page-form-section" id="edgtf_enable_social_share">
			<div class="edgtf-field-desc">
				<h4><?php esc_html_e( 'Connect with Instagram', 'themenametd' ); ?></h4>
				<p><?php esc_html_e( 'Connecting with Instagram will enable you to show your latest photos on your site', 'themenametd' ); ?></p>
			</div>
			<div class="edgtf-section-content">
				<div class="container-fluid">
					<?php
					$instagram_user_id = get_option( $instagram_api::INSTAGRAM_USER_ID );
					$connection_type   = get_option( $instagram_api::CONNECTION_TYPE );
					if ( $instagram_user_id ) { ?>
						<div class="row">
							<div class="col-lg-12">
								<p><?php echo esc_html__( 'You are currently connected to Instagram ID: ', 'themenametd' );
									echo esc_attr( $instagram_user_id ) ?></p>
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<?php if ( ! empty( $_GET['disconnect'] ) ) { ?>
							<div class="col-lg-4">
								<a class="btn btn-primary" href="<?php echo esc_url( $instagram_api->reloadURL() ); ?>"><?php echo esc_html__( 'Reload Page', 'themenametd' ); ?></a>
							</div>
						<?php } else if ( empty( $connection_type ) ) { ?>
							<div class="col-lg-4">
								<a class="btn btn-primary" href="<?php echo esc_url( $instagram_api->instagramRequestCode() ); ?>"><?php echo esc_html__( 'Connect with Instagram Personal account', 'themenametd' ); ?></a>
							</div>
							<div class="col-lg-4">
								<a class="btn btn-primary" href="<?php echo esc_url( $instagram_api->facebookRequestCode() ); ?>"><?php echo esc_html__( 'Connect with Instagram Business account', 'themenametd' ); ?></a>
							</div>
						<?php } else { ?>
							<div class="col-lg-4">
								<a class="btn btn-primary" href="<?php echo esc_url( $instagram_api->disconnectURL() ); ?>"><?php echo esc_html__( 'Disconnect Instagram account', 'themenametd' ) ?></a>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	<?php }
}

/*
   Class: ConallEdgeClassImagesVideos
   A class that initializes Edge Images Videos
*/
class ConallEdgeClassOptionsFramework implements iConallEdgeInterfaceRender {
	private $label;
	private $description;


	function __construct($label="",$description="") {
		$this->label = $label;
		$this->description = $description;
	}

	public function render($factory) {
		global $post;
		?>

		<div class="edgtf-portfolio-additional-item-holder" style="display: none">
			<div class="edgtf-portfolio-toggle-holder">
				<div class="edgtf-portfolio-toggle edgtf-toggle-desc">
					<span class="number">1</span><span class="edgtf-toggle-inner"><?php esc_html_e( 'Additional Sidebar Item ', 'conall' ); ?><em><?php esc_html_e( '(Order Number, Item Title)', 'conall' ); ?></em></span>
				</div>
				<div class="edgtf-portfolio-toggle edgtf-portfolio-control">
					<span class="toggle-portfolio-item"><i class="fa fa-caret-up"></i></span>
					<a href="#" class="remove-portfolio-item"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="edgtf-portfolio-toggle-content">
				<div class="edgtf-page-form-section">
					<div class="edgtf-section-content">
						<div class="container-fluid">
							<div class="row">

								<div class="col-lg-2">
									<em class="edgtf-field-description"><?php esc_html_e( 'Order Number', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="optionlabelordernumber_x" name="optionlabelordernumber_x" >
								</div>
								<div class="col-lg-10">
									<em class="edgtf-field-description"><?php esc_html_e( 'Item Title ', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="optionLabel_x" name="optionLabel_x" >
								</div>
							</div>
							<div class="row next-row">
								<div class="col-lg-12">
									<em class="edgtf-field-description"><?php esc_html_e( 'Item Text', 'conall' ); ?></em>
									<textarea class="form-control edgtf-input edgtf-form-element" id="optionValue_x" name="optionValue_x" ></textarea>
								</div>
							</div>
							<div class="row next-row">
								<div class="col-lg-12">
									<em class="edgtf-field-description"><?php esc_html_e( 'Enter Full URL for Item Text Link', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="optionUrl_x" name="optionUrl_x" >
								</div>
							</div>
						</div><!-- close div.edgtf-section-content -->
					</div><!-- close div.container-fluid -->
				</div>
			</div>
		</div>
		<?php
		$no = 1;
		$portfolios = get_post_meta( $post->ID, 'edgt_portfolios', true );
		if (!empty($portfolios) && count($portfolios)>1) {
			usort($portfolios, "conall_edge_compare_portfolio_options");
		}
		while (isset($portfolios[$no-1])) {
			$portfolio = $portfolios[$no-1];
			?>
			<div class="edgtf-portfolio-additional-item" rel="<?php echo esc_attr($no); ?>">
				<div class="edgtf-portfolio-toggle-holder">
					<div class="edgtf-portfolio-toggle edgtf-toggle-desc">
						<span class="number"><?php echo esc_html($no); ?></span><span class="edgtf-toggle-inner"><?php esc_html_e( 'Additional Sidebar Item - ', 'conall' ); ?><em>(<?php echo stripslashes($portfolio['optionlabelordernumber']); ?>, <?php echo stripslashes($portfolio['optionLabel']); ?>)</em></span>
					</div>
					<div class="edgtf-portfolio-toggle edgtf-portfolio-control">
						<span class="toggle-portfolio-item"><i class="fa fa-caret-down"></i></span>
						<a href="#" class="remove-portfolio-item"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="edgtf-portfolio-toggle-content" style="display: none">
					<div class="edgtf-page-form-section">
						<div class="edgtf-section-content">
							<div class="container-fluid">
								<div class="row">

									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Order Number', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="optionlabelordernumber_<?php echo esc_attr($no); ?>" name="optionlabelordernumber[]" value="<?php echo isset($portfolio['optionlabelordernumber']) ? esc_attr(stripslashes($portfolio['optionlabelordernumber'])) : ""; ?>" >
									</div>
									<div class="col-lg-10">
										<em class="edgtf-field-description"><?php esc_html_e( 'Item Title ', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="optionLabel_<?php echo esc_attr($no); ?>" name="optionLabel[]" value="<?php echo esc_attr(stripslashes($portfolio['optionLabel'])); ?>" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-12">
										<em class="edgtf-field-description"><?php esc_html_e( 'Item Text', 'conall' ); ?></em>
										<textarea class="form-control edgtf-input edgtf-form-element" id="optionValue_<?php echo esc_attr($no); ?>" name="optionValue[]" ><?php echo esc_attr(stripslashes($portfolio['optionValue'])); ?></textarea>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-12">
										<em class="edgtf-field-description"><?php esc_html_e( 'Enter Full URL for Item Text Link', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="optionUrl_<?php echo esc_attr($no); ?>" name="optionUrl[]" value="<?php echo stripslashes($portfolio['optionUrl']); ?>" >
									</div>
								</div>
							</div><!-- close div.edgtf-section-content -->
						</div><!-- close div.container-fluid -->
					</div>
				</div>
			</div>
			<?php
			$no++;
		}
		?>

		<div class="edgtf-portfolio-add">
			<a class="edgtf-add-item btn btn-sm btn-primary" href="#"><?php esc_html_e( ' Add New Item', 'conall' ); ?></a>


			<a class="edgtf-toggle-all-item btn btn-sm btn-default pull-right" href="#"><?php esc_html_e( ' Expand All', 'conall' ); ?></a>
		</div>




	<?php

	}
}

/*
   Class: ConallEdgeClassSlideElementsFramework
   A class that initializes elements for Slider
*/
class ConallEdgeClassSlideElementsFramework implements iConallEdgeInterfaceRender {
	private $label;
	private $description;


	function __construct($label="",$description="") {
		$this->label = $label;
		$this->description = $description;
	}

	public function render($factory) {
		global $post;
		global $conall_edge_fonts_array;

		$custom_positions = get_post_meta( $post->ID, 'edgtf_slide_holder_elements_alignment', true ) == 'custom';
		$default_screen_width = get_post_meta( $post->ID, 'edgtf_slide_elements_default_width', true );
		if ($default_screen_width == '') $default_screen_width = 1920;

		$screen_widths = array(
			// These values must match those in map.php (for slider), slider.php and shortcodes.js
			"mobile" => 600,
			"tabletp" => 800,
			"tabletl" => 1024,
			"laptop" => 1440
		);
		?>

		<div class="edgtf-slide-element-additional-item-holder" style="display: none">
			<div class="edgtf-slide-element-toggle-holder">
				<div class="edgtf-slide-element-toggle edgtf-toggle-desc">
					<span class="number">1</span><span class="edgtf-toggle-inner"><?php esc_html_e( 'Slide Element', 'conall' ); ?></span>
				</div>
				<div class="edgtf-slide-element-toggle edgtf-slide-element-control">
					<span class="toggle-slide-element-item"><i class="fa fa-caret-up"></i></span>
					<a href="#" class="remove-slide-element-item"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="edgtf-slide-element-toggle-content">
				<div class="edgtf-page-form-section">
					<div class="edgtf-section-content">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Element Type', 'conall' ); ?></em>
									<select class="form-control edgtf-input edgtf-form-element edgtf-slide-element-type-selector" id="slideelementtype_x" name="slideelementtype_x" >
										<option value="text"><?php esc_html_e( 'Text', 'conall' ); ?></option>
										<option value="image"><?php esc_html_e( 'Image', 'conall' ); ?></option>
										<option value="button"><?php esc_html_e( 'Button', 'conall' ); ?></option>
										<option value="section-link"><?php esc_html_e( 'Anchor Link', 'conall' ); ?></option>
									</select>
								</div>
								<div class="col-lg-9">
									<em class="edgtf-field-description"><?php esc_html_e( 'Element Name (For Your Own Reference)', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementname_x" name="slideelementname_x" >
								</div>
							</div>
							<div class="row next-row edgtf-slide-element-type-fields edgtf-setf-section-link" style="display: none">
								<div class="col-lg-12">
									<em class="edgtf-field-description"><?php esc_html_e( 'Anchor Link is always rendered at the bottom of the slide, centrally aligned.', 'conall' ); ?></em>
								</div>
							</div>
							<div class="row next-row">
								<div class="col-lg-3">
									<em class="edgtf-field-description">Element Visible?</em>
									<select class="form-control edgtf-input edgtf-form-element" id="slideelementvisible_x" name="slideelementvisible_x" >
										<option value="yes"><?php esc_html_e( 'Yes', 'conall' ); ?></option>
										<option value="no"><?php esc_html_e( 'No', 'conall' ); ?></option>
									</select>
								</div>
								<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
									<em class="edgtf-field-description"><?php esc_html_e( 'Pivot Point', 'conall' ); ?></em>
									<select class="form-control edgtf-input edgtf-form-element" id="slideelementpivot_x" name="slideelementpivot_x" >
										<option value="top-left"><?php esc_html_e( 'Top - Left', 'conall' ); ?></option>
										<option value="top-center"><?php esc_html_e( 'Top - Center', 'conall' ); ?></option>
										<option value="top-right"><?php esc_html_e( 'Top - Right', 'conall' ); ?></option>
										<option value="middle-left"><?php esc_html_e( 'Middle - Left', 'conall' ); ?></option>
										<option value="middle-center"><?php esc_html_e( 'Middle - Center', 'conall' ); ?></option>
										<option value="middle-right"><?php esc_html_e( 'Middle - Right', 'conall' ); ?></option>
										<option value="bottom-left"><?php esc_html_e( 'Bottom - Left', 'conall' ); ?></option>
										<option value="bottom-center"><?php esc_html_e( 'Bottom - Center', 'conall' ); ?></option>
										<option value="bottom-right"><?php esc_html_e( 'Bottom - Right', 'conall' ); ?></option>
									</select>
								</div>
								<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
									<em class="edgtf-field-description"><?php esc_html_e( 'Order', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementzindex_x" name="slideelementzindex_x" value="" >
								</div>
							</div>
							<div class="row next-row edgtf-slide-element-all-but-custom"<?php if ($custom_positions) { ?> style="display:none"<?php } ?>>
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Margin - Top (px)', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementmargintop_x" name="slideelementmargintop_x" >
								</div>
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Margin - Bottom (px)', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementmarginbottom_x" name="slideelementmarginbottom_x" >
								</div>
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Margin - Left (px)', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementmarginleft_x" name="slideelementmarginleft_x" >
								</div>
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Margin - Right (px)', 'conall' ); ?></em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementmarginright_x" name="slideelementmarginright_x" >
								</div>
							</div>

							<div class="edgtf-slide-element-type-fields edgtf-setf-text">
								<div class="row next-row edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative width (F/C*100 or blank for auto width)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextwidth_x" name="slideelementtextwidth_x" value="" >
									</div>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative height (G/D*100 or blank for auto height)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextheight_x" name="slideelementtextheight_x" value="" >
									</div>
								</div>
								<div class="row next-row edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextleft_x" name="slideelementtextleft_x" value="" >
									</div>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtexttop_x" name="slideelementtexttop_x" value="" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Item Text', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtext_x" name="slideelementtext_x" value="" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Link', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextlink_x" name="slideelementtextlink_x" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Target', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementtexttarget_x" name="slideelementtexttarget_x" >
											<option value="_self"><?php esc_html_e( 'Self', 'conall' ); ?></option>
											<option value="_blank"><?php esc_html_e( 'Blank', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Hover Color for Link', 'conall' ); ?></em>
										<input type="text" id="slideelementtextlinkhovercolor_x" name="slideelementtextlinkhovercolor_x" class="my-color-field"/>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Text Style', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementtextstyle_x" name="slideelementtextstyle_x" >
											<option value="small"><?php esc_html_e( 'Small Text', 'conall' ); ?></option>
											<option value="normal" selected><?php esc_html_e( 'Normal Text', 'conall' ); ?></option>
											<option value="large"><?php esc_html_e( 'Large Text', 'conall' ); ?></option>
											<option value="extra-large"><?php esc_html_e( 'Extra Large Text', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Text Style Options', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element edgtf-slide-element-options-selector-text" id="slideelementtextoptions_x" name="slideelementtextoptions_x" >
											<option value="simple"><?php esc_html_e( 'Simple', 'conall' ); ?></option>
											<option value="advanced"><?php esc_html_e( 'Advanced', 'conall' ); ?></option>
										</select>
									</div>
								</div>
								<div class="edgtf-slide-elements-advanced-text-options" style="display: none">
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Color', 'conall' ); ?></em>
											<input type="text" id="slideelementfontcolor_x" name="slideelementfontcolor_x" value="" class="my-color-field"/>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Size (px)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementfontsize_x" name="slideelementfontsize_x" value="" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Line Height (px)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementlineheight_x" name="slideelementlineheight_x" value="" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Letter Spacing (px)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementletterspacing_x" name="slideelementletterspacing_x" value="" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Family', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element"
													id="slideelementfontfamily_x"
													name="slideelementfontfamily_x"
													>
												<option value="-1"><?php esc_html_e( 'Default', 'conall' ); ?></option>
												<?php foreach($conall_edge_fonts_array as $fontArray) { ?>
													<option value="<?php echo esc_attr(str_replace(' ', '+', $fontArray["family"])); ?>"><?php echo esc_html($fontArray["family"]); ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Style', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementfontstyle_x" name="slideelementfontstyle_x" >
												<option value=""></option>
												<option value="normal"><?php esc_html_e( 'normal', 'conall' ); ?></option>
												<option value="italic"><?php esc_html_e( 'italic', 'conall' ); ?></option>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Weight', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementfontweight_x" name="slideelementfontweight_x" >
												<option value=""></option>
												<?php for ($i=1; $i<=9; $i++) { ?>
												<option value="<?php echo esc_attr($i*100); ?>"><?php echo esc_attr($i*100); ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Text Transform', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementtexttransform_x" name="slideelementtexttransform_x" >
												<option value=""></option>
												<option value="none"><?php esc_html_e( 'None', 'conall' ); ?></option>
												<option value="capitalize"><?php esc_html_e( 'Capitalize', 'conall' ); ?></option>
												<option value="uppercase"><?php esc_html_e( 'Uppercase', 'conall' ); ?></option>
												<option value="lowercase"><?php esc_html_e( 'Lowercase', 'conall' ); ?></option>
											</select>
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Background Color', 'conall' ); ?></em>
											<input type="text" id="slideelementtextbgndcolor_x; ?>" name="slideelementtextbgndcolor_x" value="" class="my-color-field"/>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Background Transparency (0-1)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextbgndtransparency_x; ?>" name="slideelementtextbgndtransparency_x" value="" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Border Thickness (px)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextborderthickness_x" name="slideelementtextborderthickness_x" value="" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Border Style', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementtextborderstyle_x" name="slideelementtextborderstyle_x" >
												<option value=""></option>
												<option value="solid"><?php esc_html_e( 'solid', 'conall' ); ?></option>
												<option value="dashed"><?php esc_html_e( 'dashed', 'conall' ); ?></option>
												<option value="dotted"><?php esc_html_e( 'dotted', 'conall' ); ?></option>
												<option value="double"><?php esc_html_e( 'double', 'conall' ); ?></option>
												<option value="groove"><?php esc_html_e( 'groove', 'conall' ); ?></option>
												<option value="ridge"><?php esc_html_e( 'ridge', 'conall' ); ?></option>
												<option value="inset"><?php esc_html_e( 'inset', 'conall' ); ?></option>
												<option value="outset"><?php esc_html_e( 'outset', 'conall' ); ?></option>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Border Color', 'conall' ); ?></em>
											<input type="text" id="slideelementtextbordercolor_x" name="slideelementtextbordercolor_x" value="" class="my-color-field"/>
										</div>
									</div>
								</div>
							</div>

							<div class="edgtf-slide-element-type-fields edgtf-setf-image" style="display: none">
								<div class="row next-row">
									<div class="col-lg-12">
										<em class="edgtf-field-description"><?php esc_html_e( 'Image', 'conall' ); ?></em>
										<div class="edgtf-media-uploader">
											<div style="display: none"
												class="edgtf-media-image-holder">
												<img src="" class="edgtf-media-image img-thumbnail"/>
											</div>
											<div style="display: none"
												 class="form-control edgtf-input edgtf-form-element edgtf-media-meta-fields">
												<input type="hidden" class="edgtf-media-upload-url"
													   id="slideelementimageurl_x"
													   name="slideelementimageurl_x"
													   value=""/>
												<input type="hidden" class="edgtf-media-upload-height"
													   name="slideelementimageuploadheight_x"
													   value=""/>
												<input type="hidden"
													   class="edgtf-media-upload-width"
													   name="slideelementimageuploadwidth_x"
													   value=""/>
											</div>
											<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
											   href="javascript:void(0)"
											   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
											   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
											<a style="display: none;" href="javascript: void(0)"
											   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
										</div>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative width (F/C*100 or blank for auto width)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimagewidth_x" name="slideelementimagewidth_x" value="" >
									</div>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative height (G/D*100 or blank for auto height)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimageheight_x" name="slideelementimageheight_x" value="" >
									</div>
								</div>
								<div class="row next-row edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimageleft_x" name="slideelementimageleft_x" value="" >
									</div>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimagetop_x" name="slideelementimagetop_x" value="" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Link', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimagelink_x" name="slideelementimagelink_x" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Target', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementimagetarget_x" name="slideelementimagetarget_x" >
											<option value="_self"><?php esc_html_e( 'Self', 'conall' ); ?></option>
											<option value="_blank"><?php esc_html_e( 'Blank', 'conall' ); ?></option>
										</select>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Border Thickness (px)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimageborderthickness_x" name="slideelementimageborderthickness_x" value="" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Border Style', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementimageborderstyle_x" name="slideelementimageborderstyle_x" >
											<option value=""></option>
											<option value="solid"><?php esc_html_e( 'solid', 'conall' ); ?></option>
											<option value="dashed"><?php esc_html_e( 'dashed', 'conall' ); ?></option>
											<option value="dotted"><?php esc_html_e( 'dotted', 'conall' ); ?></option>
											<option value="double"><?php esc_html_e( 'double', 'conall' ); ?></option>
											<option value="groove"><?php esc_html_e( 'groove', 'conall' ); ?></option>
											<option value="ridge"><?php esc_html_e( 'ridge', 'conall' ); ?></option>
											<option value="inset"><?php esc_html_e( 'inset', 'conall' ); ?></option>
											<option value="outset"><?php esc_html_e( 'outset', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Border Color', 'conall' ); ?></em>
										<input type="text" id="slideelementimagebordercolor_x" name="slideelementimagebordercolor_x" value="" class="my-color-field"/>
									</div>
								</div>
							</div>

							<div class="edgtf-slide-element-type-fields edgtf-setf-button" style="display: none">
								<div class="row next-row edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonleft_x" name="slideelementbuttonleft_x" >
									</div>
									<div class="col-lg-6">
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttontop_x" name="slideelementbuttontop_x" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Button Text', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttontext_x" name="slideelementbuttontext_x" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Link', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonlink_x" name="slideelementbuttonlink_x" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Target', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttontarget_x" name="slideelementbuttontarget_x" >
											<option value="_self"><?php esc_html_e( 'Self', 'conall' ); ?></option>
											<option value="_blank"><?php esc_html_e( 'Blank', 'conall' ); ?></option>
										</select>
									</div>
								</div>
                                <div class="row next-row">
                                    <div class="col-lg-3">
                                        <em class="edgtf-field-description"><?php esc_html_e( 'Padding - Top (px)', 'conall' ); ?></em>
                                        <input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonpaddingtop_x" name="slideelementbuttonpaddingtop_x" >
                                    </div>
                                    <div class="col-lg-3">
                                        <em class="edgtf-field-description"><?php esc_html_e( 'Padding - Bottom (px)', 'conall' ); ?></em>
                                        <input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonpaddingbottom_x" name="slideelementbuttonpaddingbottom_x" >
                                    </div>
                                    <div class="col-lg-3">
                                        <em class="edgtf-field-description"><?php esc_html_e( 'Padding - Left (px)', 'conall' ); ?></em>
                                        <input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonpaddingleft_x" name="slideelementbuttonpaddingleft_x" >
                                    </div>
                                    <div class="col-lg-3">
                                        <em class="edgtf-field-description"><?php esc_html_e( 'Padding - Right (px)', 'conall' ); ?></em>
                                        <input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonpaddingright_x" name="slideelementbuttonpaddingright_x" >
                                    </div>
                                </div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Button Size', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonsize_x" name="slideelementbuttonsize_x" >
											<option value="" ><?php esc_html_e( 'Default', 'conall' ); ?></option>
											<option value="small"><?php esc_html_e( 'Small', 'conall' ); ?></option>
											<option value="medium"><?php esc_html_e( 'Medium', 'conall' ); ?></option>
											<option value="large"><?php esc_html_e( 'Large', 'conall' ); ?></option>
											<option value="huge"><?php esc_html_e( 'Extra Large', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Button Type', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttontype_x" name="slideelementbuttontype_x" >
											<option value="" ><?php esc_html_e( 'Default', 'conall' ); ?></option>
											<option value="outline"><?php esc_html_e( 'Outline', 'conall' ); ?></option>
											<option value="solid"><?php esc_html_e( 'Solid', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-3 edgtf-slide-element-all-but-custom"<?php if ($custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description">Keep in Line with Other Buttons?</em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttoninline_x" name="slideelementbuttoninline_x" >
											<option value="no"><?php esc_html_e( 'No', 'conall' ); ?></option>
											<option value="yes"><?php esc_html_e( 'Yes', 'conall' ); ?></option>
										</select>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Font Size (px)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonfontsize_x" name="slideelementbuttonfontsize_x" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Font Weight (px)', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonfontweight_x" name="slideelementbuttonfontweight_x" >
											<option value=""></option>
											<?php for ($i=1; $i<=9; $i++) { ?>
											<option value="<?php echo esc_attr($i*100); ?>"><?php echo esc_attr($i*100); ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Font Color', 'conall' ); ?></em>
										<input type="text" id="slideelementbuttonfontcolor_x" name="slideelementbuttonfontcolor_x" class="my-color-field"/>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Font Hover Color', 'conall' ); ?></em>
										<input type="text" id="slideelementbuttonfonthovercolor_x" name="slideelementbuttonfonthovercolor_x" class="my-color-field"/>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Background Color', 'conall' ); ?></em>
										<input type="text" id="slideelementbuttonbgndcolor_x" name="slideelementbuttonbgndcolor_x" class="my-color-field"/>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Background Hover Color', 'conall' ); ?></em>
										<input type="text" id="slideelementbuttonbgndhovercolor_x" name="slideelementbuttonbgndhovercolor_x" class="my-color-field"/>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Border Color', 'conall' ); ?></em>
										<input type="text" id="slideelementbuttonbordercolor_x" name="slideelementbuttonbordercolor_x" class="my-color-field"/>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Border Hover Color', 'conall' ); ?></em>
										<input type="text" id="slideelementbuttonborderhovercolor_x" name="slideelementbuttonborderhovercolor_x" class="my-color-field"/>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Icon Pack', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element edgtf-slide-element-button-icon-pack"
												id="slideelementbuttoniconpack_x"
												name="slideelementbuttoniconpack_x"
												>
										<?php
										$icon_packs = conall_edge_icon_collections()->getIconCollectionsEmpty("no_icon");
										foreach ($icon_packs as $key=>$value) { ?>
											<option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
										<?php
										}
										?>
										</select>
									</div>
									<?php
									foreach (conall_edge_icon_collections()->iconCollections as $collection_key => $collection_object) {
									$icons_array = $collection_object->getIconsArray();
									?>
									<div class="col-lg-3 edgtf-slide-element-button-icon-collection <?php echo esc_attr($collection_key); ?>" style="display: none">
										<em class="edgtf-field-description"><?php esc_html_e( 'Button Icon', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element"
												id="slideelementbuttonicon_<?php echo esc_attr($collection_key); ?>_x"
												name="slideelementbuttonicon_<?php echo esc_attr($collection_key); ?>_x"
												>
										<?php
										foreach ($icons_array as $key=>$value) { ?>
											<option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
										<?php
										}
										?>
										</select>
									</div>
									<?php
									}
									?>
								</div>
							</div>

							<div class="edgtf-slide-element-type-fields edgtf-setf-section-link" style="display: none">
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description">Target Section Anchor (i.e. "#products")</em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementsectionlinkanchor_x" name="slideelementsectionlinkanchor_x" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description">Anchor Link Text (i.e. "Scroll Down")</em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementsectionlinktext_x" name="slideelementsectionlinktext_x" >
									</div>
								</div>
							</div>

							<div class="row next-row">
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Animation', 'conall' ); ?></em>
									<select class="form-control edgtf-input edgtf-form-element" id="slideelementanimation_x" name="slideelementanimation_x" >
										<option value="default"><?php esc_html_e( 'Default', 'conall' ); ?></option>
										<option value="none"><?php esc_html_e( 'No Animation', 'conall' ); ?></option>
										<option value="flip"><?php esc_html_e( 'Flip', 'conall' ); ?></option>
										<option value="spin"><?php esc_html_e( 'Spin', 'conall' ); ?></option>
										<option value="fade"><?php esc_html_e( 'Fade In', 'conall' ); ?></option>
										<option value="from_bottom"><?php esc_html_e( 'Fly In From Bottom', 'conall' ); ?></option>
										<option value="from_top"><?php esc_html_e( 'Fly In From Top', 'conall' ); ?></option>
										<option value="from_left"><?php esc_html_e( 'Fly In From Left', 'conall' ); ?></option>
										<option value="from_right"><?php esc_html_e( 'Fly In From Right', 'conall' ); ?></option>
									</select>
								</div>
								<div class="col-lg-3">
									<em class="edgtf-field-description">Animation Delay (i.e. "0.5s" or "400ms")</em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementanimationdelay_x" name="slideelementanimationdelay_x" >
								</div>
								<div class="col-lg-3">
									<em class="edgtf-field-description">Animation Duration (i.e. "0.5s" or "400ms")</em>
									<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementanimationduration_x" name="slideelementanimationduration_x" >
								</div>
							</div>
							<div class="row next-row">
								<div class="col-lg-3">
									<em class="edgtf-field-description"><?php esc_html_e( 'Element Responsiveness', 'conall' ); ?></em>
									<select class="form-control edgtf-input edgtf-form-element edgtf-slide-element-responsiveness-selector" id="slideelementresponsive_x" name="slideelementresponsive_x" >
										<option value="proportional"><?php esc_html_e( 'Preserve proportions', 'conall' ); ?></option>
										<option value="stages"><?php esc_html_e( 'Scale based on stage coefficients', 'conall' ); ?></option>
									</select>
								</div>
							</div>
							<div class="edgtf-slide-responsive-scale-factors" style="display:none">
								<div class="row next-row">
									<div class="col-lg-12">
										<em class="edgtf-field-description"><?php esc_html_e( 'Enter below the scale factors for each responsive stage, relative to the values above (or to the original size for images).', 'conall' ); ?><br/><?php esc_html_e( 'Scale factor of 1 leaves the element at the same size as for the default screen width of ', 'conall' ); ?><span class="edgtf-slide-dynamic-def-width"><?php echo esc_attr($default_screen_width); ?></span><?php esc_html_e( 'px, while setting it to zero hides the element.', 'conall' ); ?><div class="edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>><?php esc_html_e( 'If you also wish to change the position of the element for a certain stage, enter the desired position in the corresponding fields.', 'conall' ); ?></div></em>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Mobile', 'conall' ); ?><br>(up to <?php echo esc_attr($screen_widths["mobile"]); ?>px)</em>
									</div>
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscalemobile_x" name="slideelementscalemobile_x" placeholder="<?php esc_attr_e( '0.5', 'conall' ); ?>">
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementleftmobile_x" name="slideelementleftmobile_x" >
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtopmobile_x" name="slideelementtopmobile_x" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Tablet (Portrait)', 'conall' ); ?><br>(<?php echo esc_attr($screen_widths["mobile"])+1; ?>px - <?php echo esc_attr($screen_widths["tabletp"]); ?>px)</em>
									</div>
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscaletabletp_x" name="slideelementscaletabletp_x" placeholder="<?php esc_attr_e( '0.6', 'conall' ); ?>">
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementlefttabletp_x" name="slideelementlefttabletp_x" >
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtoptabletp_x" name="slideelementtoptabletp_x" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Tablet (Landscape)', 'conall' ); ?><br>(<?php echo esc_attr($screen_widths["tabletp"])+1; ?>px - <?php echo esc_attr($screen_widths["tabletl"]); ?>px)</em>
									</div>
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscaletabletl_x" name="slideelementscaletabletl_x" placeholder="<?php esc_attr_e( '0.7', 'conall' ); ?>">
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementlefttabletl_x" name="slideelementlefttabletl_x" >
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtoptabletl_x" name="slideelementtoptabletl_x" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Laptop', 'conall' ); ?><br>(<?php echo esc_attr($screen_widths["tabletl"])+1; ?>px - <?php echo esc_attr($screen_widths["laptop"]); ?>px)</em>
									</div>
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscalelaptop_x" name="slideelementscalelaptop_x" placeholder="<?php esc_attr_e( '0.8', 'conall' ); ?>">
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementleftlaptop_x" name="slideelementleftlaptop_x" >
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtoplaptop_x" name="slideelementtoplaptop_x" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Desktop', 'conall' ); ?><br>(above <?php echo esc_attr($screen_widths["laptop"]); ?>px)</em>
									</div>
									<div class="col-lg-2">
										<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscaledesktop_x" name="slideelementscaledesktop_x" placeholder="<?php esc_attr_e( '1', 'conall' ); ?>">
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementleftdesktop_x" name="slideelementleftdesktop_x" >
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtopdesktop_x" name="slideelementtopdesktop_x" >
									</div>
								</div>
							</div>
						</div><!-- close div.edgtf-section-content -->
					</div><!-- close div.container-fluid -->
				</div>
			</div>
		</div>
		<?php
		$no = 1;
		$slide_elements = get_post_meta( $post->ID, 'edgt_slide_elements', true );
		if (!empty($slide_elements) && count($slide_elements)>1) {
			//usort($slide_elements, "conall_edge_compare_portfolio_options");
		}
		while (isset($slide_elements[$no-1])) {
			$slide_element = $slide_elements[$no-1];
			?>
			<div class="edgtf-slide-element-additional-item" rel="<?php echo esc_attr($no); ?>">
				<div class="edgtf-slide-element-toggle-holder">
					<div class="edgtf-slide-element-toggle edgtf-toggle-desc">
						<span class="number"><?php echo esc_html($no); ?></span><span class="edgtf-toggle-inner"><?php esc_html_e( 'Slide Element - ', 'conall' ); ?><em><?php echo stripslashes($slide_element['slideelementname']); ?></em></span>
					</div>
					<div class="edgtf-slide-element-toggle edgtf-slide-element-control">
						<span class="toggle-slide-element-item"><i class="fa fa-caret-down"></i></span>
						<a href="#" class="remove-slide-element-item"><i class="fa fa-times"></i></a>
					</div>
				</div>
				<div class="edgtf-slide-element-toggle-content" style="display: none">
					<div class="edgtf-page-form-section">
						<div class="edgtf-section-content">
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Element Type', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element edgtf-slide-element-type-selector" id="slideelementtype_<?php echo esc_attr($no); ?>" name="slideelementtype[]" >
											<option value="text" <?php echo esc_attr($slide_element['slideelementtype']) == "text" ? "selected" : ""; ?>><?php esc_html_e( 'Text', 'conall' ); ?></option>
											<option value="image" <?php echo esc_attr($slide_element['slideelementtype']) == "image" ? "selected" : ""; ?>><?php esc_html_e( 'Image', 'conall' ); ?></option>
											<option value="button" <?php echo esc_attr($slide_element['slideelementtype']) == "button" ? "selected" : ""; ?>><?php esc_html_e( 'Button', 'conall' ); ?></option>
											<option value="section-link" <?php echo esc_attr($slide_element['slideelementtype']) == "section-link" ? "selected" : ""; ?>><?php esc_html_e( 'Anchor Link', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-9">
										<em class="edgtf-field-description"><?php esc_html_e( 'Element Name (For Your Own Reference)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementname_<?php esc_attr($no); ?>" name="slideelementname[]" value="<?php echo esc_attr($slide_element['slideelementname']); ?>" >
									</div>
								</div>
								<div class="row next-row edgtf-slide-element-type-fields edgtf-setf-section-link"<?php if ($slide_element['slideelementtype'] != "section-link") { ?> style="display: none"<?php } ?>>
									<div class="col-lg-12">
										<em class="edgtf-field-description"><?php esc_html_e( 'Anchor Link is always rendered at the bottom of the slide, centrally aligned.', 'conall' ); ?></em>
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description">Element Visible?</em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementvisible_<?php echo esc_attr($no); ?>" name="slideelementvisible[]" >
											<option value="yes" <?php if (isset($slide_element['slideelementvisible'])) {echo esc_attr($slide_element['slideelementvisible']) == "yes" ? "selected" : "";}  ?>><?php esc_html_e( 'Yes', 'conall' ); ?></option>
											<option value="no" <?php if (isset($slide_element['slideelementvisible'])) {echo esc_attr($slide_element['slideelementvisible']) == "no" ? "selected" : "";}  ?>><?php esc_html_e( 'No', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Pivot Point', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementpivot_<?php echo esc_attr($no); ?>" name="slideelementpivot[]" >
											<option value="top-left" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "top-left" ? "selected" : "";}  ?>><?php esc_html_e( 'Top - Left', 'conall' ); ?></option>
											<option value="top-center" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "top-center" ? "selected" : "";}  ?>><?php esc_html_e( 'Top - Center', 'conall' ); ?></option>
											<option value="top-right" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "top-right" ? "selected" : "";}  ?>><?php esc_html_e( 'Top - Right', 'conall' ); ?></option>
											<option value="middle-left" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "middle-left" ? "selected" : "";}  ?>><?php esc_html_e( 'Middle - Left', 'conall' ); ?></option>
											<option value="middle-center" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "middle-center" ? "selected" : "";}  ?>><?php esc_html_e( 'Middle - Center', 'conall' ); ?></option>
											<option value="middle-right" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "middle-right" ? "selected" : "";}  ?>><?php esc_html_e( 'Middle - Right', 'conall' ); ?></option>
											<option value="bottom-left" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "bottom-left" ? "selected" : "";}  ?>><?php esc_html_e( 'Bottom - Left', 'conall' ); ?></option>
											<option value="bottom-center" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "bottom-center" ? "selected" : "";}  ?>><?php esc_html_e( 'Bottom - Center', 'conall' ); ?></option>
											<option value="bottom-right" <?php if (isset($slide_element['slideelementpivot'])) {echo esc_attr($slide_element['slideelementpivot']) == "bottom-right" ? "selected" : "";}  ?>><?php esc_html_e( 'Bottom - Right', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<em class="edgtf-field-description"><?php esc_html_e( 'Order', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementzindex_<?php esc_attr($no); ?>" name="slideelementzindex[]" value="<?php echo isset($slide_element['slideelementzindex']) ? esc_attr($slide_element['slideelementzindex']) : ''; ?>" >
									</div>
								</div>
								<div class="row next-row edgtf-slide-element-all-but-custom"<?php if ($custom_positions) { ?> style="display:none"<?php } ?>>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Margin - Top (px)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementmargintop_<?php esc_attr($no); ?>" name="slideelementmargintop[]" value="<?php echo isset($slide_element['slideelementmargintop']) ? esc_attr($slide_element['slideelementmargintop']) : ''; ?>" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Margin - Bottom (px)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementmarginbottom_<?php esc_attr($no); ?>" name="slideelementmarginbottom[]" value="<?php echo isset($slide_element['slideelementmarginbottom']) ? esc_attr($slide_element['slideelementmarginbottom']) : ''; ?>" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Margin - Left (px)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementmarginleft_<?php esc_attr($no); ?>" name="slideelementmarginleft[]" value="<?php echo isset($slide_element['slideelementmarginleft']) ? esc_attr($slide_element['slideelementmarginleft']) : ''; ?>" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Margin - Right (px)', 'conall' ); ?></em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementmarginright_<?php esc_attr($no); ?>" name="slideelementmarginright[]" value="<?php echo isset($slide_element['slideelementmarginright']) ? esc_attr($slide_element['slideelementmarginright']) : ''; ?>" >
									</div>
								</div>

								<div class="edgtf-slide-element-type-fields edgtf-setf-text"<?php if ($slide_element['slideelementtype'] != "text") { ?> style="display: none"<?php } ?>>
									<div class="row next-row edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative width (F/C*100 or blank for auto width)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextwidth_<?php esc_attr($no); ?>" name="slideelementtextwidth[]" value="<?php echo isset($slide_element['slideelementtextwidth']) ? esc_attr($slide_element['slideelementtextwidth']) : ''; ?>" >
										</div>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative height (G/D*100 or blank for auto height)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextheight_<?php esc_attr($no); ?>" name="slideelementtextheight[]" value="<?php echo isset($slide_element['slideelementtextheight']) ? esc_attr($slide_element['slideelementtextheight']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextleft_<?php esc_attr($no); ?>" name="slideelementtextleft[]" value="<?php echo isset($slide_element['slideelementtextleft']) ? esc_attr($slide_element['slideelementtextleft']) : ''; ?>" >
										</div>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtexttop_<?php esc_attr($no); ?>" name="slideelementtexttop[]" value="<?php echo isset($slide_element['slideelementtexttop']) ? esc_attr($slide_element['slideelementtexttop']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Item Text', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtext_<?php esc_attr($no); ?>" name="slideelementtext[]" value="<?php echo esc_attr($slide_element['slideelementtext']); ?>" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Link', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextlink_<?php echo esc_attr($no); ?>" name="slideelementtextlink[]" value="<?php echo isset($slide_element['slideelementtextlink']) ? esc_attr($slide_element['slideelementtextlink']) : ''; ?>" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Target', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementtexttarget_<?php echo esc_attr($no); ?>" name="slideelementtexttarget[]" >
												<option value="_self" <?php if (isset($slide_element['slideelementtexttarget'])) {echo esc_attr($slide_element['slideelementtexttarget']) == "_self" ? "selected" : "";} ?>><?php esc_html_e( 'Self', 'conall' ); ?></option>
												<option value="_blank" <?php if (isset($slide_element['slideelementtexttarget'])) {echo esc_attr($slide_element['slideelementtexttarget']) == "_blank" ? "selected" : "";} ?>><?php esc_html_e( 'Blank', 'conall' ); ?></option>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Hover Color for Link', 'conall' ); ?></em>
											<input type="text" id="slideelementtextlinkhovercolor_<?php esc_attr($no); ?>" name="slideelementtextlinkhovercolor[]" value="<?php echo isset($slide_element['slideelementtextlinkhovercolor']) ? esc_attr($slide_element['slideelementtextlinkhovercolor']) : ''; ?>" class="my-color-field"/>
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Text Style', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementtextstyle_<?php echo esc_attr($no); ?>" name="slideelementtextstyle[]" >
												<option value="small" <?php if (isset($slide_element['slideelementtextstyle'])) {echo esc_attr($slide_element['slideelementtextstyle']) == "small" ? "selected" : "";} ?>><?php esc_html_e( 'Small Text', 'conall' ); ?></option>
												<option value="normal" <?php if (isset($slide_element['slideelementtextstyle'])) {echo esc_attr($slide_element['slideelementtextstyle']) == "normal" ? "selected" : "";} ?>><?php esc_html_e( 'Normal Text', 'conall' ); ?></option>
												<option value="large" <?php if (isset($slide_element['slideelementtextstyle'])) {echo esc_attr($slide_element['slideelementtextstyle']) == "large" ? "selected" : "";}  ?>><?php esc_html_e( 'Large Text', 'conall' ); ?></option>
												<option value="extra-large" <?php if (isset($slide_element['slideelementtextstyle'])) {echo esc_attr($slide_element['slideelementtextstyle']) == "extra-large" ? "selected" : "";} ?>><?php esc_html_e( 'Extra Large Text', 'conall' ); ?></option>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Text Style Options', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element edgtf-slide-element-options-selector edgtf-slide-element-options-selector-text" id="slideelementtextoptions_<?php echo esc_attr($no); ?>" name="slideelementtextoptions[]" >
												<option value="simple" <?php if (isset($slide_element['slideelementtextoptions'])) {echo esc_attr($slide_element['slideelementtextoptions']) == "simple" ? "selected" : "";}  ?>><?php esc_html_e( 'Simple', 'conall' ); ?></option>
												<option value="advanced" <?php if (isset($slide_element['slideelementtextoptions'])) {echo esc_attr($slide_element['slideelementtextoptions']) == "advanced" ? "selected" : "";}  ?>><?php esc_html_e( 'Advanced', 'conall' ); ?></option>
											</select>
										</div>
									</div>
									<div class="edgtf-slide-elements-advanced-text-options"<?php if (isset($slide_element['slideelementtextoptions']) && $slide_element['slideelementtextoptions'] != "advanced") { ?> style="display: none"<?php } ?>>
										<div class="row next-row">
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Font Color', 'conall' ); ?></em>
												<input type="text" id="slideelementfontcolor_<?php esc_attr($no); ?>" name="slideelementfontcolor[]" value="<?php echo esc_attr($slide_element['slideelementfontcolor']); ?>" class="my-color-field"/>
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Font Size (px)', 'conall' ); ?></em>
												<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementfontsize_<?php esc_attr($no); ?>" name="slideelementfontsize[]" value="<?php echo esc_attr($slide_element['slideelementfontsize']); ?>" >
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Line Height (px)', 'conall' ); ?></em>
												<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementlineheight_<?php esc_attr($no); ?>" name="slideelementlineheight[]" value="<?php echo esc_attr($slide_element['slideelementlineheight']); ?>" >
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Letter Spacing (px)', 'conall' ); ?></em>
												<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementletterspacing_<?php esc_attr($no); ?>" name="slideelementletterspacing[]" value="<?php echo esc_attr($slide_element['slideelementletterspacing']); ?>" >
											</div>
										</div>
										<div class="row next-row">
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Font Family', 'conall' ); ?></em>
												<select class="form-control edgtf-input edgtf-form-element"
														id="slideelementfontfamily_<?php echo esc_attr($no); ?>"
														name="slideelementfontfamily[]"
														>
													<option value="-1"><?php esc_html_e( 'Default', 'conall' ); ?></option>
													<?php foreach($conall_edge_fonts_array as $fontArray) { ?>
														<option <?php if (isset($slide_element['slideelementfontfamily']) && $slide_element['slideelementfontfamily'] == str_replace(' ', '+', $fontArray["family"])) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr(str_replace(' ', '+', $fontArray["family"])); ?>"><?php echo esc_html($fontArray["family"]); ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Font Style', 'conall' ); ?></em>
												<select class="form-control edgtf-input edgtf-form-element" id="slideelementfontstyle_<?php echo esc_attr($no); ?>" name="slideelementfontstyle[]" >
													<option value="" <?php if (isset($slide_element['slideelementfontstyle'])) {echo esc_attr($slide_element['slideelementfontstyle']) == "" ? "selected" : "";} ?>></option>
													<option value="normal" <?php if (isset($slide_element['slideelementfontstyle'])) {echo esc_attr($slide_element['slideelementfontstyle']) == "normal" ? "selected" : "";}  ?>><?php esc_html_e( 'normal', 'conall' ); ?></option>
													<option value="italic" <?php if (isset($slide_element['slideelementfontstyle'])) {echo esc_attr($slide_element['slideelementfontstyle']) == "italic" ? "selected" : "";} ?>><?php esc_html_e( 'italic', 'conall' ); ?></option>
												</select>
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Font Weight', 'conall' ); ?></em>
												<select class="form-control edgtf-input edgtf-form-element" id="slideelementfontweight_<?php echo esc_attr($no); ?>" name="slideelementfontweight[]" >
													<option value="" <?php if (isset($slide_element['slideelementfontweight'])) {echo esc_attr($slide_element['slideelementfontweight']) == "" ? "selected" : "";} ?>></option>
													<?php for ($i=1; $i<=9; $i++) { ?>
													<option value="<?php echo esc_attr($i*100); ?>" <?php if (isset($slide_element['slideelementfontweight'])) {echo (int)$slide_element['slideelementfontweight'] == $i*100 ? "selected" : "";} ?>><?php echo esc_attr($i*100); ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Text Transform', 'conall' ); ?></em>
												<select class="form-control edgtf-input edgtf-form-element" id="slideelementtexttransform_<?php echo esc_attr($no); ?>" name="slideelementtexttransform[]" >
													<option value="" <?php if (isset($slide_element['slideelementtexttransform'])) {echo esc_attr($slide_element['slideelementtexttransform']) == "" ? "selected" : "";} ?>></option>
													<option value="none" <?php if (isset($slide_element['slideelementtexttransform'])) {echo esc_attr($slide_element['slideelementtexttransform']) == "none" ? "selected" : "";}  ?>><?php esc_html_e( 'None', 'conall' ); ?></option>
													<option value="capitalize" <?php if (isset($slide_element['slideelementtexttransform'])) {echo esc_attr($slide_element['slideelementtexttransform']) == "capitalize" ? "selected" : "";}  ?>><?php esc_html_e( 'Capitalize', 'conall' ); ?></option>
													<option value="uppercase" <?php if (isset($slide_element['slideelementtexttransform'])) {echo esc_attr($slide_element['slideelementtexttransform']) == "uppercase" ? "selected" : "";} ?>><?php esc_html_e( 'Uppercase', 'conall' ); ?></option>
													<option value="lowercase" <?php if (isset($slide_element['slideelementtexttransform'])) {echo esc_attr($slide_element['slideelementtexttransform']) == "lowercase" ? "selected" : "";} ?>><?php esc_html_e( 'Lowercase', 'conall' ); ?></option>
												</select>
											</div>
										</div>
										<div class="row next-row">
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Background Color', 'conall' ); ?></em>
												<input type="text" id="slideelementtextbgndcolor_<?php esc_attr($no); ?>" name="slideelementtextbgndcolor[]" value="<?php echo isset($slide_element['slideelementtextbgndcolor']) ? esc_attr($slide_element['slideelementtextbgndcolor']) : ''; ?>" class="my-color-field"/>
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Background Transparency (0-1)', 'conall' ); ?></em>
												<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextbgndtransparency_<?php esc_attr($no); ?>" name="slideelementtextbgndtransparency[]" value="<?php echo isset($slide_element['slideelementtextbgndtransparency']) ? esc_attr($slide_element['slideelementtextbgndtransparency']) : ''; ?>" >
											</div>
										</div>
										<div class="row next-row">
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Border Thickness (px)', 'conall' ); ?></em>
												<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtextborderthickness_<?php esc_attr($no); ?>" name="slideelementtextborderthickness[]" value="<?php echo isset($slide_element['slideelementtextborderthickness']) ? esc_attr($slide_element['slideelementtextborderthickness']) : ''; ?>" >
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Border Style', 'conall' ); ?></em>
												<select class="form-control edgtf-input edgtf-form-element" id="slideelementtextborderstyle_<?php echo esc_attr($no); ?>" name="slideelementtextborderstyle[]" >
													<option value="" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "" ? "selected" : "";} ?>></option>
													<option value="solid" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "solid" ? "selected" : "";}  ?>><?php esc_html_e( 'solid', 'conall' ); ?></option>
													<option value="dashed" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "dashed" ? "selected" : "";}  ?>><?php esc_html_e( 'dashed', 'conall' ); ?></option>
													<option value="dotted" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "dotted" ? "selected" : "";} ?>><?php esc_html_e( 'dotted', 'conall' ); ?></option>
													<option value="double" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "double" ? "selected" : "";} ?>><?php esc_html_e( 'double', 'conall' ); ?></option>
													<option value="groove" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "groove" ? "selected" : "";}  ?>><?php esc_html_e( 'groove', 'conall' ); ?></option>
													<option value="ridge" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "ridge" ? "selected" : "";}  ?>><?php esc_html_e( 'ridge', 'conall' ); ?></option>
													<option value="inset" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "inset" ? "selected" : "";} ?>><?php esc_html_e( 'inset', 'conall' ); ?></option>
													<option value="outset" <?php if (isset($slide_element['slideelementtextborderstyle'])) {echo esc_attr($slide_element['slideelementtextborderstyle']) == "outset" ? "selected" : "";} ?>><?php esc_html_e( 'outset', 'conall' ); ?></option>
												</select>
											</div>
											<div class="col-lg-3">
												<em class="edgtf-field-description"><?php esc_html_e( 'Border Color', 'conall' ); ?></em>
												<input type="text" id="slideelementtextbordercolor_<?php esc_attr($no); ?>" name="slideelementtextbordercolor[]" value="<?php echo isset($slide_element['slideelementtextbordercolor']) ? esc_attr($slide_element['slideelementtextbordercolor']) : ''; ?>" class="my-color-field"/>
											</div>
										</div>
									</div>
								</div>

								<div class="edgtf-slide-element-type-fields edgtf-setf-image"<?php if ($slide_element['slideelementtype'] != "image") { ?> style="display: none"<?php } ?>>
									<div class="row next-row">
										<div class="col-lg-12">
											<em class="edgtf-field-description"><?php esc_html_e( 'Image', 'conall' ); ?></em>
											<div class="edgtf-media-uploader">
												<div<?php if ($slide_element['slideelementimageurl'] == "") { ?> style="display: none"<?php } ?>
													class="edgtf-media-image-holder">
													<img src="<?php if ($slide_element['slideelementimageurl'] != "") { echo esc_url(conall_edge_get_attachment_thumb_url($slide_element['slideelementimageurl'])); } ?>"
														 class="edgtf-media-image img-thumbnail"/>
												</div>
												<div style="display: none"
													 class="form-control edgtf-input edgtf-form-element edgtf-media-meta-fields">
													<input type="hidden" class="edgtf-media-upload-url"
														   id="slideelementimageurl_<?php esc_attr($no); ?>"
														   name="slideelementimageurl[]"
														   value="<?php echo esc_attr($slide_element['slideelementimageurl']); ?>"/>
													<input type="hidden" class="edgtf-media-upload-height"
														   name="slideelementimageuploadheight[]"
														   value="<?php echo esc_attr($slide_element['slideelementimageuploadheight']); ?>"/>
													<input type="hidden"
														   class="edgtf-media-upload-width"
														   name="slideelementimageuploadwidth[]"
														   value="<?php echo esc_attr($slide_element['slideelementimageuploadwidth']); ?>"/>
												</div>
												<a class="edgtf-media-upload-btn btn btn-sm btn-primary"
												   href="javascript:void(0)"
												   data-frame-title="<?php esc_attr_e('Select Image', 'conall'); ?>"
												   data-frame-button-text="<?php esc_attr_e('Select Image', 'conall'); ?>"><?php esc_html_e('Upload', 'conall'); ?></a>
												<a style="display: none;" href="javascript: void(0)"
												   class="edgtf-media-remove-btn btn btn-default btn-sm"><?php esc_html_e('Remove', 'conall'); ?></a>
											</div>
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative width (F/C*100 or blank for auto width)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimagewidth_<?php esc_attr($no); ?>" name="slideelementimagewidth[]" value="<?php echo isset($slide_element['slideelementimagewidth']) ? esc_attr($slide_element['slideelementimagewidth']) : ''; ?>" >
										</div>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative height (G/D*100 or blank for auto height)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimageheight_<?php esc_attr($no); ?>" name="slideelementimageheight[]" value="<?php echo isset($slide_element['slideelementimageheight']) ? esc_attr($slide_element['slideelementimageheight']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimageleft_<?php esc_attr($no); ?>" name="slideelementimageleft[]" value="<?php echo isset($slide_element['slideelementimageleft']) ? esc_attr($slide_element['slideelementimageleft']) : ''; ?>" >
										</div>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimagetop_<?php esc_attr($no); ?>" name="slideelementimagetop[]" value="<?php echo isset($slide_element['slideelementimagetop']) ? esc_attr($slide_element['slideelementimagetop']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Link', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimagelink_<?php echo esc_attr($no); ?>" name="slideelementimagelink[]" value="<?php echo isset($slide_element['slideelementimagelink']) ? esc_attr($slide_element['slideelementimagelink']) : ''; ?>" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Target', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementimagetarget_<?php echo esc_attr($no); ?>" name="slideelementimagetarget[]" >
												<option value="_self" <?php if (isset($slide_element['slideelementimagetarget'])) {echo esc_attr($slide_element['slideelementimagetarget']) == "_self" ? "selected" : "";} ?>><?php esc_html_e( 'Self', 'conall' ); ?></option>
												<option value="_blank" <?php if (isset($slide_element['slideelementimagetarget'])) {echo esc_attr($slide_element['slideelementimagetarget']) == "_blank" ? "selected" : "";} ?>><?php esc_html_e( 'Blank', 'conall' ); ?></option>
											</select>
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Border Thickness (px)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementimageborderthickness_<?php esc_attr($no); ?>" name="slideelementimageborderthickness[]" value="<?php echo isset($slide_element['slideelementimageborderthickness']) ? esc_attr($slide_element['slideelementimageborderthickness']) : ''; ?>" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Border Style', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementimageborderstyle_<?php echo esc_attr($no); ?>" name="slideelementimageborderstyle[]" >
												<option value="" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "" ? "selected" : "";} ?>></option>
												<option value="solid" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "solid" ? "selected" : "";}  ?>><?php esc_html_e( 'solid', 'conall' ); ?></option>
												<option value="dashed" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "dashed" ? "selected" : "";}  ?>><?php esc_html_e( 'dashed', 'conall' ); ?></option>
												<option value="dotted" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "dotted" ? "selected" : "";} ?>><?php esc_html_e( 'dotted', 'conall' ); ?></option>
												<option value="double" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "double" ? "selected" : "";} ?>><?php esc_html_e( 'double', 'conall' ); ?></option>
												<option value="groove" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "groove" ? "selected" : "";}  ?>><?php esc_html_e( 'groove', 'conall' ); ?></option>
												<option value="ridge" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "ridge" ? "selected" : "";}  ?>><?php esc_html_e( 'ridge', 'conall' ); ?></option>
												<option value="inset" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "inset" ? "selected" : "";} ?>><?php esc_html_e( 'inset', 'conall' ); ?></option>
												<option value="outset" <?php if (isset($slide_element['slideelementimageborderstyle'])) {echo esc_attr($slide_element['slideelementimageborderstyle']) == "outset" ? "selected" : "";} ?>><?php esc_html_e( 'outset', 'conall' ); ?></option>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Border Color', 'conall' ); ?></em>
											<input type="text" id="slideelementimagebordercolor_<?php esc_attr($no); ?>" name="slideelementimagebordercolor[]" value="<?php echo isset($slide_element['slideelementimagebordercolor']) ? esc_attr($slide_element['slideelementimagebordercolor']) : ''; ?>" class="my-color-field"/>
										</div>
									</div>
								</div>

								<div class="edgtf-slide-element-type-fields edgtf-setf-button"<?php if ($slide_element['slideelementtype'] != "button") { ?> style="display: none"<?php } ?>>
									<div class="row next-row edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonleft_<?php esc_attr($no); ?>" name="slideelementbuttonleft[]" value="<?php echo isset($slide_element['slideelementbuttonleft']) ? esc_attr($slide_element['slideelementbuttonleft']) : ''; ?>" >
										</div>
										<div class="col-lg-6">
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttontop_<?php esc_attr($no); ?>" name="slideelementbuttontop[]" value="<?php echo isset($slide_element['slideelementbuttontop']) ? esc_attr($slide_element['slideelementbuttontop']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Button Text', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttontext_<?php echo esc_attr($no); ?>" name="slideelementbuttontext[]" value="<?php echo isset($slide_element['slideelementbuttontext']) ? esc_attr($slide_element['slideelementbuttontext']) : ''; ?>" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Link', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonlink_<?php echo esc_attr($no); ?>" name="slideelementbuttonlink[]" value="<?php echo isset($slide_element['slideelementbuttonlink']) ? esc_attr($slide_element['slideelementbuttonlink']) : ''; ?>" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Target', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttontarget_<?php echo esc_attr($no); ?>" name="slideelementbuttontarget[]" >
												<option value="_self" <?php if (isset($slide_element['slideelementbuttontarget'])) {echo esc_attr($slide_element['slideelementbuttontarget']) == "_self" ? "selected" : "";} ?>><?php esc_html_e( 'Self', 'conall' ); ?></option>
												<option value="_blank" <?php if (isset($slide_element['slideelementbuttontarget'])) {echo esc_attr($slide_element['slideelementbuttontarget']) == "_blank" ? "selected" : "";} ?>><?php esc_html_e( 'Blank', 'conall' ); ?></option>
											</select>
										</div>
									</div>
                                    <div class="row next-row">
                                        <div class="col-lg-3">
                                            <em class="edgtf-field-description"><?php esc_html_e( 'Padding - Top (px)', 'conall' ); ?></em>
                                            <input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonpaddingtop<?php echo esc_attr($no); ?>" name="slideelementbuttonpaddingtop[]" value="<?php echo isset($slide_element['slideelementbuttonpaddingtop']) ? esc_attr($slide_element['slideelementbuttonpaddingtop']) : ''; ?>" >
                                        </div>
                                        <div class="col-lg-3">
                                            <em class="edgtf-field-description"><?php esc_html_e( 'Padding - Bottom (px)', 'conall' ); ?></em>
                                            <input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonpaddingbottom<?php echo esc_attr($no); ?>" name="slideelementbuttonpaddingbottom[]" value="<?php echo isset($slide_element['slideelementbuttonpaddingbottom']) ? esc_attr($slide_element['slideelementbuttonpaddingbottom']) : ''; ?>" >
                                        </div>
                                        <div class="col-lg-3">
                                            <em class="edgtf-field-description"><?php esc_html_e( 'Padding - Left (px)', 'conall' ); ?></em>
                                            <input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonpaddingleft<?php echo esc_attr($no); ?>" name="slideelementbuttonpaddingleft[]" value="<?php echo isset($slide_element['slideelementbuttonpaddingleft']) ? esc_attr($slide_element['slideelementbuttonpaddingleft']) : ''; ?>" >
                                        </div>
                                        <div class="col-lg-3">
                                            <em class="edgtf-field-description"><?php esc_html_e( 'Padding - Right (px)', 'conall' ); ?></em>
                                            <input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonpaddingright<?php echo esc_attr($no); ?>" name="slideelementbuttonpaddingright[]" value="<?php echo isset($slide_element['slideelementbuttonpaddingright']) ? esc_attr($slide_element['slideelementbuttonpaddingright']) : ''; ?>" >
                                        </div>
                                    </div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Button Size', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonsize_<?php echo esc_attr($no); ?>" name="slideelementbuttonsize[]" >
												<option value="" <?php if (isset($slide_element['slideelementbuttonsize'])) {echo esc_attr($slide_element['slideelementbuttonsize']) == "" ? "selected" : "";} ?>><?php esc_html_e( 'Default', 'conall' ); ?></option>
												<option value="small" <?php if (isset($slide_element['slideelementbuttonsize'])) {echo esc_attr($slide_element['slideelementbuttonsize']) == "small" ? "selected" : "";}  ?>><?php esc_html_e( 'Small', 'conall' ); ?></option>
												<option value="medium" <?php if (isset($slide_element['slideelementbuttonsize'])) {echo esc_attr($slide_element['slideelementbuttonsize']) == "medium" ? "selected" : "";} ?>><?php esc_html_e( 'Medium', 'conall' ); ?></option>
												<option value="large" <?php if (isset($slide_element['slideelementbuttonsize'])) {echo esc_attr($slide_element['slideelementbuttonsize']) == "large" ? "selected" : "";} ?>><?php esc_html_e( 'Large', 'conall' ); ?></option>
												<option value="huge" <?php if (isset($slide_element['slideelementbuttonsize'])) {echo esc_attr($slide_element['slideelementbuttonsize']) == "huge" ? "selected" : "";} ?>><?php esc_html_e( 'Extra Large', 'conall' ); ?></option>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Button Type', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttontype_<?php echo esc_attr($no); ?>" name="slideelementbuttontype[]" >
												<option value="" <?php if (isset($slide_element['slideelementbuttontype'])) {echo esc_attr($slide_element['slideelementbuttontype']) == "" ? "selected" : "";} ?>><?php esc_html_e( 'Default', 'conall' ); ?></option>
												<option value="outline" <?php if (isset($slide_element['slideelementbuttontype'])) {echo esc_attr($slide_element['slideelementbuttontype']) == "outline" ? "selected" : "";}  ?>><?php esc_html_e( 'Outline', 'conall' ); ?></option>
												<option value="solid" <?php if (isset($slide_element['slideelementbuttontype'])) {echo esc_attr($slide_element['slideelementbuttontype']) == "solid" ? "selected" : "";} ?>><?php esc_html_e( 'Solid', 'conall' ); ?></option>
											</select>
										</div>
										<div class="col-lg-3 edgtf-slide-element-all-but-custom"<?php if ($custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description">Keep in Line with Other Buttons?</em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttoninline_<?php echo esc_attr($no); ?>" name="slideelementbuttoninline[]" >
												<option value="no" <?php if (isset($slide_element['slideelementbuttoninline'])) {echo esc_attr($slide_element['slideelementbuttoninline']) == "no" ? "selected" : "";}  ?>><?php esc_html_e( 'No', 'conall' ); ?></option>
												<option value="yes" <?php if (isset($slide_element['slideelementbuttoninline'])) {echo esc_attr($slide_element['slideelementbuttoninline']) == "yes" ? "selected" : "";} ?>><?php esc_html_e( 'Yes', 'conall' ); ?></option>
											</select>
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Size (px)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonfontsize_<?php echo esc_attr($no); ?>" name="slideelementbuttonfontsize[]" value="<?php echo isset($slide_element['slideelementbuttonfontsize']) ? esc_attr($slide_element['slideelementbuttonfontsize']) : ''; ?>" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Weight (px)', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element" id="slideelementbuttonfontweight_<?php echo esc_attr($no); ?>" name="slideelementbuttonfontweight[]" >
												<option value="" <?php if (isset($slide_element['slideelementbuttonfontweight'])) {echo esc_attr($slide_element['slideelementbuttonfontweight']) == "" ? "selected" : "";} ?>></option>
												<?php for ($i=1; $i<=9; $i++) { ?>
												<option value="<?php echo esc_attr($i*100); ?>" <?php if (isset($slide_element['slideelementbuttonfontweight'])) {echo (int)$slide_element['slideelementbuttonfontweight'] == $i*100 ? "selected" : "";} ?>><?php echo esc_attr($i*100); ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Color', 'conall' ); ?></em>
											<input type="text" id="slideelementbuttonfontcolor_<?php esc_attr($no); ?>" name="slideelementbuttonfontcolor[]" value="<?php echo isset($slide_element['slideelementbuttonfontcolor']) ? esc_attr($slide_element['slideelementbuttonfontcolor']) : ''; ?>" class="my-color-field"/>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Font Hover Color', 'conall' ); ?></em>
											<input type="text" id="slideelementbuttonfonthovercolor_<?php esc_attr($no); ?>" name="slideelementbuttonfonthovercolor[]" value="<?php echo isset($slide_element['slideelementbuttonfonthovercolor']) ? esc_attr($slide_element['slideelementbuttonfonthovercolor']) : ''; ?>" class="my-color-field"/>
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Background Color', 'conall' ); ?></em>
											<input type="text" id="slideelementbuttonbgndcolor_<?php esc_attr($no); ?>" name="slideelementbuttonbgndcolor[]" value="<?php echo isset($slide_element['slideelementbuttonbgndcolor']) ? esc_attr($slide_element['slideelementbuttonbgndcolor']) : ''; ?>" class="my-color-field"/>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Background Hover Color', 'conall' ); ?></em>
											<input type="text" id="slideelementbuttonbgndhovercolor_<?php esc_attr($no); ?>" name="slideelementbuttonbgndhovercolor[]" value="<?php echo isset($slide_element['slideelementbuttonbgndhovercolor']) ? esc_attr($slide_element['slideelementbuttonbgndhovercolor']) : ''; ?>" class="my-color-field"/>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Border Color', 'conall' ); ?></em>
											<input type="text" id="slideelementbuttonbordercolor_<?php esc_attr($no); ?>" name="slideelementbuttonbordercolor[]" value="<?php echo isset($slide_element['slideelementbuttonbordercolor']) ? esc_attr($slide_element['slideelementbuttonbordercolor']) : ''; ?>" class="my-color-field"/>
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Border Hover Color', 'conall' ); ?></em>
											<input type="text" id="slideelementbuttonborderhovercolor_<?php esc_attr($no); ?>" name="slideelementbuttonborderhovercolor[]" value="<?php echo isset($slide_element['slideelementbuttonborderhovercolor']) ? esc_attr($slide_element['slideelementbuttonborderhovercolor']) : ''; ?>" class="my-color-field"/>
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description"><?php esc_html_e( 'Icon Pack', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element edgtf-slide-element-button-icon-pack"
													id="slideelementbuttoniconpack_<?php echo esc_attr($no); ?>"
													name="slideelementbuttoniconpack[]"
													>
											<?php
											$icon_packs = conall_edge_icon_collections()->getIconCollectionsEmpty("no_icon");
											foreach ($icon_packs as $key=>$value) { ?>
												<option <?php if (isset($slide_element['slideelementbuttoniconpack']) && $slide_element['slideelementbuttoniconpack'] == $key) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
											<?php
											}
											?>
											</select>
										</div>
										<?php
										foreach (conall_edge_icon_collections()->iconCollections as $collection_key => $collection_object) {
										$icons_array = $collection_object->getIconsArray();
										?>
										<div class="col-lg-3 edgtf-slide-element-button-icon-collection <?php echo esc_attr($collection_key); ?>"<?php if (!isset($slide_element['slideelementbuttoniconpack']) || $slide_element['slideelementbuttoniconpack'] != $collection_key) { ?> style="display: none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Button Icon', 'conall' ); ?></em>
											<select class="form-control edgtf-input edgtf-form-element"
													id="slideelementbuttonicon_<?php echo esc_attr($collection_key).'_'.esc_attr($no); ?>"
													name="slideelementbuttonicon_<?php echo esc_attr($collection_key); ?>[]"
													>
											<?php
											foreach ($icons_array as $key=>$value) { ?>
												<option <?php if (isset($slide_element['slideelementbuttonicon_'.$collection_key]) && $slide_element['slideelementbuttonicon_'.$collection_key] == $key) { echo "selected='selected'"; } ?>  value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($value); ?></option>
											<?php
											}
											?>
											</select>
										</div>
										<?php
										}
										?>
									</div>
								</div>

								<div class="edgtf-slide-element-type-fields edgtf-setf-section-link"<?php if ($slide_element['slideelementtype'] != "section-link") { ?> style="display: none"<?php } ?>>
									<div class="row next-row">
										<div class="col-lg-3">
											<em class="edgtf-field-description">Target Section Anchor (i.e. "#products")</em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementsectionlinkanchor_<?php esc_attr($no); ?>" name="slideelementsectionlinkanchor[]" value="<?php echo isset($slide_element['slideelementsectionlinkanchor']) ? esc_attr($slide_element['slideelementsectionlinkanchor']) : ''; ?>" >
										</div>
										<div class="col-lg-3">
											<em class="edgtf-field-description">Anchor Link Text (i.e. "Scroll Down")</em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementsectionlinktext_<?php esc_attr($no); ?>" name="slideelementsectionlinktext[]" value="<?php echo isset($slide_element['slideelementsectionlinktext']) ? esc_attr($slide_element['slideelementsectionlinktext']) : ''; ?>" >
										</div>
									</div>
								</div>

								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Animation', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element" id="slideelementanimation_<?php echo esc_attr($no); ?>" name="slideelementanimation[]" >
											<option value="default" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "default" ? "selected" : "";}  ?>><?php esc_html_e( 'Default', 'conall' ); ?></option>
											<option value="none" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "none" ? "selected" : "";}  ?>><?php esc_html_e( 'No Animation', 'conall' ); ?></option>
											<option value="flip" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "flip" ? "selected" : "";}  ?>><?php esc_html_e( 'Flip', 'conall' ); ?></option>
											<option value="Spin" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "Spin" ? "selected" : "";}  ?>><?php esc_html_e( 'Spin', 'conall' ); ?></option>
											<option value="fade" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "fade" ? "selected" : "";}  ?>><?php esc_html_e( 'Fade In', 'conall' ); ?></option>
											<option value="from_bottom" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "from_bottom" ? "selected" : "";}  ?>><?php esc_html_e( 'Fly In From Bottom', 'conall' ); ?></option>
											<option value="from_top" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "from_top" ? "selected" : "";}  ?>><?php esc_html_e( 'Fly In From Top', 'conall' ); ?></option>
											<option value="from_left" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "from_left" ? "selected" : "";}  ?>><?php esc_html_e( 'Fly In From Left', 'conall' ); ?></option>
											<option value="from_right" <?php if (isset($slide_element['slideelementanimation'])) {echo esc_attr($slide_element['slideelementanimation']) == "from_right" ? "selected" : "";}  ?>><?php esc_html_e( 'Fly In From Right', 'conall' ); ?></option>
										</select>
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description">Animation Delay (i.e. "0.5s" or "400ms")</em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementanimationdelay_<?php esc_attr($no); ?>" name="slideelementanimationdelay[]" value="<?php echo isset($slide_element['slideelementanimationdelay']) ? esc_attr($slide_element['slideelementanimationdelay']) : ''; ?>" >
									</div>
									<div class="col-lg-3">
										<em class="edgtf-field-description">Animation Duration (i.e. "0.5s" or "400ms")</em>
										<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementanimationduration_<?php esc_attr($no); ?>" name="slideelementanimationduration[]" value="<?php echo isset($slide_element['slideelementanimationduration']) ? esc_attr($slide_element['slideelementanimationduration']) : ''; ?>" >
									</div>
								</div>
								<div class="row next-row">
									<div class="col-lg-3">
										<em class="edgtf-field-description"><?php esc_html_e( 'Element Responsiveness', 'conall' ); ?></em>
										<select class="form-control edgtf-input edgtf-form-element edgtf-slide-element-responsiveness-selector" id="slideelementresponsive_<?php echo esc_attr($no); ?>" name="slideelementresponsive[]" >
											<option value="proportional" <?php if (isset($slide_element['slideelementresponsive'])) {echo esc_attr($slide_element['slideelementresponsive']) == "proportional" ? "selected" : "";}  ?>><?php esc_html_e( 'Preserve proportions', 'conall' ); ?></option>
											<option value="stages" <?php if (isset($slide_element['slideelementresponsive'])) {echo esc_attr($slide_element['slideelementresponsive']) == "stages" ? "selected" : "";}  ?>><?php esc_html_e( 'Scale based on stage coefficients', 'conall' ); ?></option>
										</select>
									</div>
								</div>
								<div class="edgtf-slide-responsive-scale-factors"<?php if (isset($slide_element['slideelementresponsive']) && $slide_element['slideelementresponsive'] == 'proportional') { ?> style="display:none"<?php } ?>>
									<div class="row next-row">
										<div class="col-lg-12">
											<em class="edgtf-field-description"><?php esc_html_e( 'Enter below the scale factors for each responsive stage, relative to the values above (or to the original size for images).', 'conall' ); ?><br/><?php esc_html_e( 'Scale factor of 1 leaves the element at the same size as for the default screen width of ', 'conall' ); ?><span class="edgtf-slide-dynamic-def-width"><?php echo esc_attr($default_screen_width); ?></span><?php esc_html_e( 'px, while setting it to zero hides the element.', 'conall' ); ?><div class="edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>><?php esc_html_e( 'If you also wish to change the position of the element for a certain stage, enter the desired position in the corresponding fields.', 'conall' ); ?></div></em>
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Mobile', 'conall' ); ?><br>(up to <?php echo esc_attr($screen_widths["mobile"]); ?>px)</em>
										</div>
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscalemobile_<?php esc_attr($no); ?>" name="slideelementscalemobile[]" value="<?php echo isset($slide_element['slideelementscalemobile']) ? esc_attr($slide_element['slideelementscalemobile']) : ''; ?>" placeholder="<?php esc_attr_e( '0.5', 'conall' ); ?>">
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementleftmobile_<?php esc_attr($no); ?>" name="slideelementleftmobile[]" value="<?php echo isset($slide_element['slideelementleftmobile']) ? esc_attr($slide_element['slideelementleftmobile']) : ''; ?>" >
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtopmobile_<?php esc_attr($no); ?>" name="slideelementtopmobile[]" value="<?php echo isset($slide_element['slideelementtopmobile']) ? esc_attr($slide_element['slideelementtopmobile']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Tablet (Portrait)', 'conall' ); ?><br>(<?php echo esc_attr($screen_widths["mobile"])+1; ?>px - <?php echo esc_attr($screen_widths["tabletp"]); ?>px)</em>
										</div>
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscaletabletp_<?php esc_attr($no); ?>" name="slideelementscaletabletp[]" value="<?php echo isset($slide_element['slideelementscaletabletp']) ? esc_attr($slide_element['slideelementscaletabletp']) : ''; ?>" placeholder="<?php esc_attr_e( '0.6', 'conall' ); ?>">
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementlefttabletp_<?php esc_attr($no); ?>" name="slideelementlefttabletp[]" value="<?php echo isset($slide_element['slideelementlefttabletp']) ? esc_attr($slide_element['slideelementlefttabletp']) : ''; ?>" >
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtoptabletp_<?php esc_attr($no); ?>" name="slideelementtoptabletp[]" value="<?php echo isset($slide_element['slideelementtoptabletp']) ? esc_attr($slide_element['slideelementtoptabletp']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Tablet (Landscape)', 'conall' ); ?><br>(<?php echo esc_attr($screen_widths["tabletp"])+1; ?>px - <?php echo esc_attr($screen_widths["tabletl"]); ?>px)</em>
										</div>
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscaletabletl_<?php esc_attr($no); ?>" name="slideelementscaletabletl[]" value="<?php echo isset($slide_element['slideelementscaletabletl']) ? esc_attr($slide_element['slideelementscaletabletl']) : ''; ?>" placeholder="<?php esc_attr_e( '0.7', 'conall' ); ?>">
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementlefttabletl_<?php esc_attr($no); ?>" name="slideelementlefttabletl[]" value="<?php echo isset($slide_element['slideelementlefttabletl']) ? esc_attr($slide_element['slideelementlefttabletl']) : ''; ?>" >
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtoptabletl_<?php esc_attr($no); ?>" name="slideelementtoptabletl[]" value="<?php echo isset($slide_element['slideelementtoptabletl']) ? esc_attr($slide_element['slideelementtoptabletl']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Laptop', 'conall' ); ?><br>(<?php echo esc_attr($screen_widths["tabletl"])+1; ?>px - <?php echo esc_attr($screen_widths["laptop"]); ?>px)</em>
										</div>
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscalelaptop_<?php esc_attr($no); ?>" name="slideelementscalelaptop[]" value="<?php echo isset($slide_element['slideelementscalelaptop']) ? esc_attr($slide_element['slideelementscalelaptop']) : ''; ?>" placeholder="<?php esc_attr_e( '0.8', 'conall' ); ?>">
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementleftlaptop_<?php esc_attr($no); ?>" name="slideelementleftlaptop[]" value="<?php echo isset($slide_element['slideelementleftlaptop']) ? esc_attr($slide_element['slideelementleftlaptop']) : ''; ?>" >
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtoplaptop_<?php esc_attr($no); ?>" name="slideelementtoplaptop[]" value="<?php echo isset($slide_element['slideelementtoplaptop']) ? esc_attr($slide_element['slideelementtoplaptop']) : ''; ?>" >
										</div>
									</div>
									<div class="row next-row">
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Desktop', 'conall' ); ?><br>(above <?php echo esc_attr($screen_widths["laptop"]); ?>px)</em>
										</div>
										<div class="col-lg-2">
											<em class="edgtf-field-description"><?php esc_html_e( 'Scale Factor', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementscaledesktop_<?php esc_attr($no); ?>" name="slideelementscaledesktop[]" value="<?php echo isset($slide_element['slideelementscaledesktop']) ? esc_attr($slide_element['slideelementscaledesktop']) : ''; ?>" placeholder="<?php esc_attr_e( '1', 'conall' ); ?>">
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Left (X/C*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementleftdesktop_<?php esc_attr($no); ?>" name="slideelementleftdesktop[]" value="<?php echo isset($slide_element['slideelementleftdesktop']) ? esc_attr($slide_element['slideelementleftdesktop']) : ''; ?>" >
										</div>
										<div class="col-lg-3 edgtf-slide-element-custom-only"<?php if (!$custom_positions) { ?> style="display:none"<?php } ?>>
											<em class="edgtf-field-description"><?php esc_html_e( 'Relative position - Top (Y/D*100)', 'conall' ); ?></em>
											<input type="text" class="form-control edgtf-input edgtf-form-element" id="slideelementtopdesktop_<?php esc_attr($no); ?>" name="slideelementtopdesktop[]" value="<?php echo isset($slide_element['slideelementtopdesktop']) ? esc_attr($slide_element['slideelementtopdesktop']) : ''; ?>" >
										</div>
									</div>
								</div>
							</div><!-- close div.edgtf-section-content -->
						</div><!-- close div.container-fluid -->
					</div>
				</div>
			</div>
			<?php
			$no++;
		}
		?>

		<div class="edgtf-slide-element-add">
			<a class="edgtf-add-item btn btn-sm btn-primary" href="#"><?php esc_html_e( ' Add New Item', 'conall' ); ?></a>


			<a class="edgtf-toggle-all-item btn btn-sm btn-default pull-right" href="#"><?php esc_html_e( ' Expand All', 'conall' ); ?></a>
		</div>
	<?php
	}
}

/*
   Class: ConallEdgeClassHolderFrameScheme
   A class that initializes elements for Slider
*/
class ConallEdgeClassHolderFrameScheme implements iConallEdgeInterfaceRender {
	private $label;
	private $description;


	function __construct($label="",$description="") {
		$this->label = $label;
		$this->description = $description;
	}

	public function render($factory) { ?>

		<div class="edgtf-slide-elements-holder-frame-scheme"><img src="<?php echo esc_url(EDGE_ASSETS_ROOT . '/img/holder-frame-scheme.png'); ?>"></div>
	<?php
	}
}