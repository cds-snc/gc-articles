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
}
