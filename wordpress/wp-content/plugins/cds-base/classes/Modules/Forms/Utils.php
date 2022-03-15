<?php

namespace CDS\Modules\Forms;

class Utils
{
    public function __construct()
    {
    }

    public static function isNonceErrorMessage(array $POST): string
    {
        $message = __('400 Bad Request', 'cds-snc');

        if (!isset($POST['cds-form-nonce'])) {
            return $message;
        }

        if (!wp_verify_nonce($POST['cds-form-nonce'], 'cds_form_nonce_action')) {
            return $message;
        }

        return '';
    }

    public static function textField(string $id, string $label, ?string $description = null, ?string $value = '', ?string $placeholder = null, ?bool $echo = true)
    {
        $isEmail = $id === 'email';
        $isRequired = !str_ends_with($id, "optional");
        ob_start();
        ?>
        <div class="focus-group">
            <label class="gc-label" for="<?php echo $id ?>" id="<?php echo $id ?>-label"><?php echo $label ?></label>
            <?php if ($description) { ?>
                <div id="<?php echo $id ?>-desc" class="gc-description" data-testid="description"><?php echo $description; ?></div>
            <?php } ?>
            <input
                <?php if ($isEmail) {
                    echo 'type="email" autocomplete="email"';
                } else {
                    echo 'type="text"';
                }?>
                id="<?php echo $id ?>"
                name="<?php echo $id ?>"
                value="<?php echo $value ?>"
                <?php if ($placeholder) {
                    echo 'placeholder="' . $placeholder . '"';
                } ?>
                <?php if ($isRequired) {
                    echo 'required';
                } ?>
                class="gc-input-text"
            />
        </div>
        <?php

        $field = ob_get_contents();
        ob_end_clean();

        if (!$echo) {
            return $field;
        }
        echo $field;
    }

    public static function radioField(string $name, string $id, string $value, ?bool $echo = true)
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

        if (!$echo) {
            return $field;
        }
        echo $field;
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

    public static function submitButton(string $label): string
    {
        ob_start();
        ?>
        <div class="buttons">
            <button class="gc-button" type="submit" id="submit"><?php echo $label; ?></button>
        </div>
        <?php

        $field = ob_get_contents();
        ob_end_clean();
        return $field;
    }
}
