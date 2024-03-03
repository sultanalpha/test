<link rel='stylesheet' type='text/css' media='screen' href='/test/bootstrap/custom_input/custom_input.css'>

<?php
function setInput($input_name, $input_type)
{
?>
    <div class="input-<?php echo $input_name; ?> input-data">
        <input type="<?php echo $input_type; ?>" id="<?php echo $input_name; ?>-placeholder" placeholder="">
        <label id="<?php echo $input_name; ?>-label"></label>
        <?php
        if ($input_type === "password") {
        ?>
            <img src="/test/icons/password/icons8-hide-password-24.png" height="24" width="24" id="password-visibility">
        <?php
        }
        ?>
    </div>
<?php
}
?>