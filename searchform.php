<?php
/**
 * The Search Form in the sidebar area.
 *
 * @package WordPress
 * @subpackage e-Teacher
 * @since e-Teacher 2.0
 */
?>


	<form method="get" class="search-form" id="search-form" action="<?php bloginfo( 'home' ); ?>/">
	<div>
		<input class="search-text" type="text" name="s" id="search-text" value="" />
		<input class="search-submit btn btn-primary" type="submit" name="submit" id="search-submit" value="Search" />
	</div>
	</form>
