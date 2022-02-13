<?php
function i_amir_net_workreap_role()
{
    // gets the simple_role role object
    $role = get_role('administrator');

    // add a new capability
    $role->add_cap('i_amir_net_workreap_manage', true);
}

// add simple_role capabilities, priority must be after the initial role definition
add_action('init', 'i_amir_net_workreap_role', 11);
