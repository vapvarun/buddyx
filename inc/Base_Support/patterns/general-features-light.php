<?php
/**
 * Features block light
 *
 * @package buddyx
 */

return array(
	'title'      => __( 'Features block light', 'buddyx' ),
	'categories' => array( 'buddyx-general' ),
	'content'    => '<!-- wp:columns {"verticalAlignment":"bottom","align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|70"}}}} -->
    <div class="wp-block-columns alignwide are-vertically-aligned-bottom" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:column {"verticalAlignment":"bottom","width":"60%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
    <div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:60%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
    <div class="wp-block-group"><!-- wp:group {"align":"full","className":"","style":{"border":{"radius":"100px","width":"1px","color":"#cecece6b"},"spacing":{"blockGap":"8px","padding":{"top":"4px","right":"12px","bottom":"4px","left":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|basecolor"}}}},"backgroundColor":"contrastcolor","textColor":"basecolor","layout":{"type":"flex","flexWrap":"nowrap"}} -->
    <div class="wp-block-group alignfull has-border-color has-basecolor-color has-contrastcolor-background-color has-text-color has-background has-link-color" style="border-color:#cecece6b;border-width:1px;border-radius:100px;padding-top:4px;padding-right:12px;padding-bottom:4px;padding-left:12px"><!-- wp:paragraph {"align":"left","className":"text-nowrap is-style-default","style":{"typography":{"fontSize":"14px"}}} -->
    <p class="has-text-align-left text-nowrap is-style-default" style="font-size:14px">Design System</p>
    <!-- /wp:paragraph --></div>
    <!-- /wp:group -->

    <!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.2","fontSize":"50px"},"elements":{"link":{"color":{"text":"#111111"}}},"color":{"text":"#111111"}}} -->
    <p class="has-text-color has-link-color" style="color:#111111;font-size:50px;font-style:normal;font-weight:600;line-height:1.2">Provide powerful<br><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">Solutions at all times</mark></p>
    <!-- /wp:paragraph --></div>
    <!-- /wp:group --></div>
    <!-- /wp:column -->

    <!-- wp:column {"verticalAlignment":"bottom","width":"40%","layout":{"type":"default"}} -->
    <div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:40%"><!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem"}}} -->
    <p style="font-size:1rem">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s</p>
    <!-- /wp:paragraph --></div>
    <!-- /wp:column --></div>
    <!-- /wp:columns -->

    <!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}}} -->
    <div class="wp-block-columns alignwide" style="margin-bottom:var(--wp--preset--spacing--50)"><!-- wp:column {"className":"is-style-softborder","style":{"border":{"radius":"10px","color":"#e8e8e8","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
    <div class="wp-block-column is-style-softborder has-border-color has-base-background-color has-background" style="border-color:#e8e8e8;border-width:1px;border-radius:10px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:image {"width":"30px","height":"30px","scale":"cover"} -->
    <figure class="wp-block-image is-resized"><img src="data:image/svg+xml,%3Csvg width=&quot;18&quot; height=&quot;18&quot; viewBox=&quot;0 0 18 18&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath fill-rule=&quot;evenodd&quot; clip-rule=&quot;evenodd&quot; d=&quot;M0.000976562 3.5.0.000976562 1.567 1.56798 0 3.50098 0C5.43397 0 7.00098 1.567 7.00098 3.5V5H11.001V3.5C11.001 1.567 12.568 0 14.501 0C16.434 0 18.001 1.567 18.001 3.5C18.001 5.433 16.434 7 14.501 7H13.001V11H14.501C16.434 11 18.001 12.567 18.001 14.5C18.001 16.433 16.434 18 14.501 18C12.568 18 11.001 16.433 11.001 14.5V13H7.00098V14.5C7.00098 16.433 5.43397 18 3.50098 18C1.56798 18 0.000976562 16.433 0.000976562 14.5.0.000976562 12.567 1.56798 11 3.50098 11H5.0.098V7H3.50098C1.56798 7 0.000976562 5.433 0.000976562 3.5ZM5.0.098 5V3.5C5.0.098 2.67157 4.3294 2 3.50098 2C2.67255 2 2.00098 2.67157 2.00098 3.5C2.00098 4.32843 2.67255 5 3.50098 5H5.0.098ZM7.00098 7V11H11.001V7H7.00098ZM5.0.098 13H3.50098C2.67255 13 2.00098 13.6716 2.00098 14.5C2.00098 15.3284 2.67255 16 3.50098 16C4.3294 16 5.0.098 15.3284 5.0.098 14.5V13ZM13.001 13V14.5C13.001 15.3284 13.6726 16 14.501 16C15.3294 16 16.001 15.3284 16.001 14.5C16.001 13.6716 15.3294 13 14.501 13H13.001ZM13.001 5H14.501C15.3294 5 16.001 4.32843 16.001 3.5C16.001 2.67157 15.3294 2 14.501 2C13.6725 2 13.001 2.67157 13.001 3.5V5Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3C/svg%3E%0A" alt="" style="object-fit:cover;width:30px;height:30px"/></figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"align":"left","className":"full-width text-nowrap","style":{"typography":{"lineHeight":"1.3","fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"#111111"}}},"color":{"text":"#111111"}},"fontSize":"medium"} -->
    <p class="has-text-align-left full-width text-nowrap has-text-color has-link-color has-medium-font-size" style="color:#111111;font-style:normal;font-weight:500;line-height:1.3">Extremely Fast</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"align":"left","className":"full-width","fontSize":"small"} -->
    <p class="has-text-align-left full-width has-small-font-size">Lorem ipsum dolor sit amet elit consectetur adipiscing vestibulum.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons"><!-- wp:button {"className":"is-style-systemdark","style":{"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"0.2rem","bottom":"0.2rem"}}},"fontSize":"xsmall"} -->
    <div class="wp-block-button has-custom-font-size is-style-systemdark has-xsmall-font-size"><a class="wp-block-button__link wp-element-button" style="padding-top:0.2rem;padding-right:var(--wp--preset--spacing--30);padding-bottom:0.2rem;padding-left:var(--wp--preset--spacing--30)">Read more</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div>
    <!-- /wp:column -->

    <!-- wp:column {"className":"is-style-softborder","style":{"border":{"radius":"10px","color":"#e8e8e8","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
    <div class="wp-block-column is-style-softborder has-border-color has-base-background-color has-background" style="border-color:#e8e8e8;border-width:1px;border-radius:10px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:image {"width":"30px"} -->
    <figure class="wp-block-image is-resized"><img src="data:image/svg+xml,%3Csvg width=&quot;21&quot; height=&quot;17&quot; viewBox=&quot;0 0 21 17&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M5 4C5 4.55228 4.55228 5 4 5C3.44772 5 3 4.55228 3 4C3 3.44772 3.44772 3 4 3C4.55228 3 5 3.44772 5 4Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M8 4C8 4.55228 7.55228 5 7 5C6.44772 5 6 4.55228 6 4C6 3.44772 6.44772 3 7 3C7.55228 3 8 3.44772 8 4Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M10 5C10.5523 5 11 4.55228 11 4C11 3.44772 10.5523 3 10 3C9.44772 3 9 3.44772 9 4C9 4.55228 9.44772 5 10 5Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M0 1C0 0.447715 0.447715 0 1 0H19C19.5523 0 20 0.447715 20 1V4C20 4.55228 19.5523 5 19 5C18.4477 5 18 4.55228 18 4V2H2V14H8C8.55228 14 9 14.4477 9 15C9 15.5523 8.55228 16 8 16H1C0.447715 16 0 15.5523 0 15V1Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M16 7C16.5523 7 17 7.44772 17 8C17 9.26682 17.2816 9.95076 17.6654 10.3346C18.0492 10.7184 18.7332 11 20 11C20.5523 11 21 11.4477 21 12C21 12.5523 20.5523 13 20 13C18.7332 13 18.0492 13.2816 17.6654 13.6654C17.2816 14.0492 17 14.7332 17 16C17 16.5523 16.5523 17 16 17C15.4477 17 15 16.5523 15 16C15 14.7332 14.7184 14.0492 14.3346 13.6654C13.9508 13.2816 13.2668 13 12 13C11.4477 13 11 12.5523 11 12C11 11.4477 11.4477 11 12 11C13.2668 11 13.9508 10.7184 14.3346 10.3346C14.7184 9.95076 15 9.26682 15 8C15 7.44772 15.4477 7 16 7Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3C/svg%3E%0A" alt="" style="width:30px"/></figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"align":"left","className":"full-width text-nowrap","style":{"typography":{"lineHeight":"1.3","fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"#111111"}}},"color":{"text":"#111111"}},"fontSize":"medium"} -->
    <p class="has-text-align-left full-width text-nowrap has-text-color has-link-color has-medium-font-size" style="color:#111111;font-style:normal;font-weight:500;line-height:1.3">Customize Everything</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"align":"left","className":"full-width","fontSize":"small"} -->
    <p class="has-text-align-left full-width has-small-font-size">Lorem ipsum dolor sit amet elit consectetur adipiscing vestibulum.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons"><!-- wp:button {"textAlign":"left","className":"is-style-systemdark","style":{"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"0.2rem","bottom":"0.2rem"}}},"fontSize":"xsmall"} -->
    <div class="wp-block-button has-custom-font-size is-style-systemdark has-xsmall-font-size"><a class="wp-block-button__link has-text-align-left wp-element-button" style="padding-top:0.2rem;padding-right:var(--wp--preset--spacing--30);padding-bottom:0.2rem;padding-left:var(--wp--preset--spacing--30)">Read more</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div>
    <!-- /wp:column -->

    <!-- wp:column {"className":"is-style-softborder","style":{"border":{"radius":"10px","color":"#e8e8e8","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
    <div class="wp-block-column is-style-softborder has-border-color has-base-background-color has-background" style="border-color:#e8e8e8;border-width:1px;border-radius:10px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:image {"width":"30px","height":"30px","scale":"cover"} -->
    <figure class="wp-block-image is-resized"><img src="data:image/svg+xml,%3Csvg width=&quot;19&quot; height=&quot;19&quot; viewBox=&quot;0 0 19 19&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M15.7071 0.292887C15.3166 -0.0976315 14.6834 -0.0976285 14.2929 0.292893L8.29289 6.29289C8.10536 6.48043 8 6.73478 8 7V10C8 10.2653 8.10536 10.5196 8.29289 10.7071C8.48043 10.8947 8.73478 11 9 11L12 11C12.2652 11 12.5196 10.8947 12.7071 10.7071L18.7071 4.70705C18.8946 4.51951 19 4.26515 19 3.99994C19 3.73472 18.8946 3.48037 18.7071 3.29283L15.7071 0.292887Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M3 9.00004C1.34315 9.00004 0 10.3432 0 12C0 13.6569 1.34315 15 3 15H13C13.5523 15 14 15.4478 14 16C14 16.5523 13.5523 17 13 17H9C8.44772 17 8 17.4478 8 18C8 18.5523 8.44772 19 9 19H13C14.6569 19 16 17.6569 16 16C16 14.3432 14.6569 13 13 13H3C2.44772 13 2 12.5523 2 12C2 11.4478 2.44772 11 3 11H5C5.55228 11 6 10.5523 6 10C6 9.44775 5.55228 9.00004 5 9.00004H3Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3C/svg%3E%0A" alt="" style="object-fit:cover;width:30px;height:30px"/></figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"align":"left","className":"full-width text-nowrap","style":{"typography":{"lineHeight":"1.3","fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"#111111"}}},"color":{"text":"#111111"}},"fontSize":"medium"} -->
    <p class="has-text-align-left full-width text-nowrap has-text-color has-link-color has-medium-font-size" style="color:#111111;font-style:normal;font-weight:500;line-height:1.3">Social Marketplace</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"align":"left","className":"full-width","fontSize":"small"} -->
    <p class="has-text-align-left full-width has-small-font-size">Lorem ipsum dolor sit amet elit consectetur adipiscing vestibulum.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons"><!-- wp:button {"textAlign":"left","className":"is-style-systemdark","style":{"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"0.2rem","bottom":"0.2rem"}}},"fontSize":"xsmall"} -->
    <div class="wp-block-button has-custom-font-size is-style-systemdark has-xsmall-font-size"><a class="wp-block-button__link has-text-align-left wp-element-button" style="padding-top:0.2rem;padding-right:var(--wp--preset--spacing--30);padding-bottom:0.2rem;padding-left:var(--wp--preset--spacing--30)">Read more</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div>
    <!-- /wp:column --></div>
    <!-- /wp:columns -->

    <!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|80"}}}} -->
    <div class="wp-block-columns alignwide" style="margin-bottom:var(--wp--preset--spacing--80)"><!-- wp:column {"className":"is-style-softborder","style":{"border":{"radius":"10px","color":"#e8e8e8","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
    <div class="wp-block-column is-style-softborder has-border-color has-base-background-color has-background" style="border-color:#e8e8e8;border-width:1px;border-radius:10px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:image {"width":"30px","height":"30px","scale":"cover"} -->
    <figure class="wp-block-image is-resized"><img src="data:image/svg+xml,%3Csvg width=&quot;18&quot; height=&quot;18&quot; viewBox=&quot;0 0 18 18&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M4 0C4.55228 0 5 0.447715 5 1V3H7C7.55228 3 8 3.44772 8 4C8 4.55228 7.55228 5 7 5H5V7C5 7.55228 4.55228 8 4 8C3.44772 8 3 7.55228 3 7V5H1C0.447715 5 0 4.55228 0 4C0 3.44772 0.447715 3 1 3H3V1C3 0.447715 3.44772 0 4 0Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M14 0C11.7909 0 10 1.79086 10 4C10 6.20914 11.7909 8 14 8C16.2091 8 18 6.20914 18 4C18 1.79086 16.2091 0 14 0Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M16.8284 12.5854C17.219 12.1949 17.219 11.5617 16.8284 11.1712C16.4379 10.7807 15.8047 10.7807 15.4142 11.1712L14 12.5854L12.5858 11.1712C12.1953 10.7807 11.5621 10.7807 11.1716 11.1712C10.781 11.5617 10.781 12.1949 11.1716 12.5854L12.5858 13.9996L11.1716 15.4138C10.781 15.8044 10.781 16.4375 11.1716 16.8281C11.5621 17.2186 12.1953 17.2186 12.5858 16.8281L14 15.4138L15.4142 16.8281C15.8047 17.2186 16.4379 17.2186 16.8284 16.8281C17.219 16.4375 17.219 15.8044 16.8284 15.4138L15.4142 13.9996L16.8284 12.5854Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M1 10C0.447715 10 0 10.4477 0 11V17C0 17.5523 0.447715 18 1 18H7C7.55228 18 8 17.5523 8 17V11C8 10.4477 7.55228 10 7 10H1Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3C/svg%3E%0A" alt="" style="object-fit:cover;width:30px;height:30px"/></figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"align":"left","className":"full-width text-nowrap","style":{"typography":{"lineHeight":"1.3","fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"#111111"}}},"color":{"text":"#111111"}},"fontSize":"medium"} -->
    <p class="has-text-align-left full-width text-nowrap has-text-color has-link-color has-medium-font-size" style="color:#111111;font-style:normal;font-weight:500;line-height:1.3">World Class Security</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"align":"left","className":"full-width","fontSize":"small"} -->
    <p class="has-text-align-left full-width has-small-font-size">Lorem ipsum dolor sit amet elit consectetur adipiscing vestibulum.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons"><!-- wp:button {"className":"is-style-systemdark","style":{"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"0.2rem","bottom":"0.2rem"}}},"fontSize":"xsmall"} -->
    <div class="wp-block-button has-custom-font-size is-style-systemdark has-xsmall-font-size"><a class="wp-block-button__link wp-element-button" style="padding-top:0.2rem;padding-right:var(--wp--preset--spacing--30);padding-bottom:0.2rem;padding-left:var(--wp--preset--spacing--30)">Read more</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div>
    <!-- /wp:column -->

    <!-- wp:column {"className":"is-style-softborder","style":{"border":{"radius":"10px","color":"#e8e8e8","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
    <div class="wp-block-column is-style-softborder has-border-color has-base-background-color has-background" style="border-color:#e8e8e8;border-width:1px;border-radius:10px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:image {"width":"30px","height":"30px","scale":"cover"} -->
    <figure class="wp-block-image is-resized"><img src="data:image/svg+xml,%3Csvg width=&quot;19&quot; height=&quot;22&quot; viewBox=&quot;0 0 19 22&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M12 7C12 6.44772 11.5523 6 11 6C10.4477 6 10 6.44772 10 7C10 9.30849 9.48919 10.7424 8.61581 11.6158C7.74243 12.4892 6.30849 13 4 13C3.44772 13 3 13.4477 3 14C3 14.5523 3.44772 15 4 15C6.30849 15 7.74243 15.5108 8.61581 16.3842C9.48919 17.2576 10 18.6915 10 21C10 21.5523 10.4477 22 11 22C11.5523 22 12 21.5523 12 21C12 18.6915 12.5108 17.2576 13.3842 16.3842C14.2576 15.5108 15.6915 15 18 15C18.5523 15 19 14.5523 19 14C19 13.4477 18.5523 13 18 13C15.6915 13 14.2576 12.4892 13.3842 11.6158C12.5108 10.7424 12 9.30849 12 7Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M4 4.5C4 4.22386 3.77614 4 3.5 4C3.22386 4 3 4.22386 3 4.5C3 5.48063 2.78279 6.0726 2.4277 6.4277C2.0726 6.78279 1.48063 7 0.5 7C0.223858 7 0 7.22386 0 7.5C0 7.77614 0.223858 8 0.5 8C1.48063 8 2.0726 8.21721 2.4277 8.5723C2.78279 8.9274 3 9.51937 3 10.5C3 10.7761 3.22386 11 3.5 11C3.77614 11 4 10.7761 4 10.5C4 9.51937 4.21721 8.9274 4.5723 8.5723C4.9274 8.21721 5.51937 8 6.5 8C6.77614 8 7 7.77614 7 7.5C7 7.22386 6.77614 7 6.5 7C5.51937 7 4.9274 6.78279 4.5723 6.4277C4.21721 6.0726 4 5.48063 4 4.5Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3Cpath d=&quot;M9 0.5C9 0.223858 8.77614 0 8.5 0C8.22386 0 8 0.223858 8 0.5C8 1.13341 7.85918 1.47538 7.66728 1.66728C7.47538 1.85918 7.13341 2 6.5 2C6.22386 2 6 2.22386 6 2.5C6 2.77614 6.22386 3 6.5 3C7.13341 3 7.47538 3.14082 7.66728 3.33272C7.85918 3.52462 8 3.86659 8 4.5C8 4.77614 8.22386 5 8.5 5C8.77614 5 9 4.77614 9 4.5C9 3.86659 9.14082 3.52462 9.33272 3.33272C9.52462 3.14082 9.86659 3 10.5 3C10.7761 3 11 2.77614 11 2.5C11 2.22386 10.7761 2 10.5 2C9.86659 2 9.52462 1.85918 9.33272 1.66728C9.14082 1.47538 9 1.13341 9 0.5Z&quot; fill=&quot;%23FF4B0E&quot;/%3E%3C/svg%3E%0A" alt="" style="object-fit:cover;width:30px;height:30px"/></figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"align":"left","className":"full-width text-nowrap","style":{"typography":{"lineHeight":"1.3","fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"#111111"}}},"color":{"text":"#111111"}},"fontSize":"medium"} -->
    <p class="has-text-align-left full-width text-nowrap has-text-color has-link-color has-medium-font-size" style="color:#111111;font-style:normal;font-weight:500;line-height:1.3">WooCommerce Ready</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"align":"left","className":"full-width","fontSize":"small"} -->
    <p class="has-text-align-left full-width has-small-font-size">Lorem ipsum dolor sit amet elit consectetur adipiscing vestibulum.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons"><!-- wp:button {"className":"is-style-systemdark","style":{"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"0.2rem","bottom":"0.2rem"}}},"fontSize":"xsmall"} -->
    <div class="wp-block-button has-custom-font-size is-style-systemdark has-xsmall-font-size"><a class="wp-block-button__link wp-element-button" style="padding-top:0.2rem;padding-right:var(--wp--preset--spacing--30);padding-bottom:0.2rem;padding-left:var(--wp--preset--spacing--30)">Read more</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div>
    <!-- /wp:column -->

    <!-- wp:column {"className":"is-style-softborder","style":{"border":{"radius":"10px","color":"#e8e8e8","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|60","right":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|40"}},"backgroundColor":"base"} -->
    <div class="wp-block-column is-style-softborder has-border-color has-base-background-color has-background" style="border-color:#e8e8e8;border-width:1px;border-radius:10px;padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--60)"><!-- wp:image {"width":"30px","height":"30px","scale":"cover"} -->
    <figure class="wp-block-image is-resized"><img src="data:image/svg+xml,%3Csvg width=&quot;18&quot; height=&quot;18&quot; viewBox=&quot;0 0 18 18&quot; fill=&quot;none&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M1 5V13M6.5 1V9M11.5 9V17M17 5V13&quot; stroke=&quot;%23FF4B0E&quot; stroke-width=&quot;2&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot;/%3E%3C/svg%3E%0A" alt="" style="object-fit:cover;width:30px;height:30px"/></figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"align":"left","className":"full-width text-nowrap","style":{"typography":{"lineHeight":"1.3","fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"#111111"}}},"color":{"text":"#111111"}},"fontSize":"medium"} -->
    <p class="has-text-align-left full-width text-nowrap has-text-color has-link-color has-medium-font-size" style="color:#111111;font-style:normal;font-weight:500;line-height:1.3">Core design options</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph {"align":"left","className":"full-width","fontSize":"small"} -->
    <p class="has-text-align-left full-width has-small-font-size">Lorem ipsum dolor sit amet elit consectetur adipiscing vestibulum.</p>
    <!-- /wp:paragraph -->

    <!-- wp:buttons -->
    <div class="wp-block-buttons"><!-- wp:button {"className":"is-style-systemdark","style":{"spacing":{"padding":{"left":"var:preset|spacing|30","right":"var:preset|spacing|30","top":"0.2rem","bottom":"0.2rem"}}},"fontSize":"xsmall"} -->
    <div class="wp-block-button has-custom-font-size is-style-systemdark has-xsmall-font-size"><a class="wp-block-button__link wp-element-button" style="padding-top:0.2rem;padding-right:var(--wp--preset--spacing--30);padding-bottom:0.2rem;padding-left:var(--wp--preset--spacing--30)">Read more</a></div>
    <!-- /wp:button --></div>
    <!-- /wp:buttons --></div>
    <!-- /wp:column --></div>
    <!-- /wp:columns -->',
);
