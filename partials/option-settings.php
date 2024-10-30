<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<form name="<?php echo $this->namespace; ?>-option_settings-form" method="post" action="">
<input type="hidden" name="<?php echo $this->hidden_field_name; ?>" value="Y" />

<p><label for="<?php echo $this->actionable_roles_field_name; ?>"><?php _e("Roles who can access restricted posts;", $this->namespace ); ?></label>
<select multiple="multiple" name="<?php echo $this->actionable_roles_field_name; ?>[]" id="<?php echo $this->actionable_roles_field_name; ?>">

<?php

$roles =$this->return_roles();


foreach ($roles as $role ) {

?>
<option value="<?php echo $role['role']; ?>"  <?php if (in_array($role['role'], $this->options[ $this->actionable_roles_field_name ])) { echo 'selected="selected"';  } ?>  ><?php echo $role['name']; ?></option>
<?php

}

?>

</select> 


<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>
</form>