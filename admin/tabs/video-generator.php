<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<h1><?php esc_html_e('Video Generator','syrus-ai') ?></h1>

<div class="mt-4">
    <h2>Settings</h2>
    <label for="api-key-input">Api Key</label>
    <div class="input-group">
        <input type="text" class="w-100" id="api-key-input">
    </div>

    <div class="mt-4">
        <button class="btn btn-primary">Save</button>
    </div>
</div>
