<form role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ) ?>">
  
    <div>
        <label class="screen-reader-text" for="s"><?php _x( 'Search for:', 'label' )?></label>
        <input type="search" id="s" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" />
        <button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
    </div>
    
</form>