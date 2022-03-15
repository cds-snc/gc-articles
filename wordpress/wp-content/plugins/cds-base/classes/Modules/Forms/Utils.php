<?php

namespace CDS\Modules\Forms;

class Utils
{
    public function __construct()
    {
    }

    public static function radioField(string $name, string $id, string $value): string
    {
        ob_start();
        ?>
        <div class="gc-input-radio">
            <input
                type="radio"
                name="<?php echo $name; ?>"
                id="<?php echo sanitize_title($id); ?>"
                value="<?php echo $id; ?>"
                class="gc-radio__input"
                required
            />
            <label for="<?php echo sanitize_title($id); ?>" class="gc-radio-label">
            <span class="radio-label-text"><?php echo $value; ?></span>
            </label
            >
        </div>
        <?php

        $field = ob_get_contents();
        ob_end_clean();
        return $field;
    }

    public static function checkboxField(string $name, string $id, string $value, array|string $vals = null, string $ariaControls = null): string
    {
        // set to empty array if a non-array is passed in
        $vals = is_array($vals) ? $vals : [];
        $checked = in_array($value, $vals);

        ob_start();
        ?>
        <div class="gc-input-checkbox">
            <input
                type="checkbox"
                name="<?php echo $name; ?>"
                id="<?php echo sanitize_title($id); ?>"
                value="<?php echo $id; ?>"
                <?php if ($checked) {
                    echo 'checked';
                } ?>
                <?php if ($ariaControls) {
                    echo 'aria-controls="' . $ariaControls . '" ';
                    echo 'aria-expanded="' . $checked . '" ';
                } ?>
                class="gc-input-checkbox__input"
            />
            <label for="<?php echo sanitize_title($id); ?>" class="gc-checkbox-label">
            <span class="checkbox-label-text"><?php echo $value; ?></span>
            </label
            >
        </div>
        <?php

        $field = ob_get_contents();
        ob_end_clean();
        return $field;
    }
}
