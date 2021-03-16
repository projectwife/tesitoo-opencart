<?php
namespace DBI;
final class Html_generator {
    private $registry;
    public function __construct($registry) {
        $this->registry = $registry;
        $this->prefix = VERSION >= '3.0.0.0' ? $registry->get('dbi_meta')['type'] . '_' : '';
        $this->namePrefix = $this->prefix . $registry->get('dbi_meta')['ext_id'] . '_';
    }

    public function __get($name) {
        return $this->registry->get($name);
    }

    public function setLangs($langs) {
        $this->langs = $langs;
        return $this;
    }

    // Private helpers
    private function getLabel($labelText, $labelFor, $helpText, $helperText) {
        $html = '';
        $labelEl = $helpText ? '<span data-toggle="tooltip" title="'.$helpText.'">'.$labelText.'</span>' : $labelText;
        $helperEl = $helperText ? '<div class="help-text">'.$helperText.'</div>' : '';

        if(VERSION >= '2.0.0.0') {
            $html .= '<label class="col-sm-3 control-label" for="'.$labelFor.'">'.$labelEl.$helperEl.'</label>';
        } else {
            $html .= $labelText;
            $html .= $helpText ? '<br/><span class="help">'.$helpText.'</span>' : '';
            $html .= $helperText ? '<br/><span class="help">'.$helperText.'</span>' : '' ;
        }

        return $html;
    }

    // Inputs
    public function getInputHorizontal($varName, $value, $required=False, $size=6) {
        $html = '';
        $id = $varName;
        $name = $this->namePrefix . $varName;
        $labelText = $this->language->get('entry_'.$varName) . ($required ? ' *' : '');
        $placeholder = $this->language->get('entry_'.$varName);
        $helpText = isset($this->langs['help_'.$id]) ? $this->langs['help_'.$id] : '';
        $helperText = isset($this->langs['helper_'.$id]) ? $this->langs['helper_'.$id] : '';
        $labelHtml = $this->getLabel($labelText, $name, $helpText, $helperText);

        if(VERSION >= '2.0.0.0') {
            $html .= '<div class="form-group">
                        ' . $labelHtml . '
                        <div class="col-sm-' . $size . '">
                            <input type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" placeholder="' . $placeholder . '" class="form-control">
                        </div>
                    </div>';
        } else {
            $html = '<tr>
                        <td>'.$labelHtml.'</td>
                        <td>
                            <input type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" placeholder="' . $placeholder . '" class="form-control">
                        </td>
                    </tr>';
        }
        return $html;
    }

    public function getMultilineHorizontal($varName, $value, $required=False, $size=6, $rows=3) {
        $html = '';
        $id = $varName;
        $name = $this->namePrefix . $varName;
        $labelText = $this->language->get('entry_'.$varName) . ($required ? ' *' : '');
        $placeholder = $this->language->get('entry_'.$varName);
        $helpText = isset($this->langs['help_'.$id]) ? $this->langs['help_'.$id] : '';
        $helperText = isset($this->langs['helper_'.$id]) ? $this->langs['helper_'.$id] : '';
        $labelHtml = $this->getLabel($labelText, $name, $helpText, $helperText);

        if(VERSION >= '2.0.0.0') {
            $html .= '<div class="form-group">
                        '.$labelHtml.'
                        <div class="col-sm-'. $size . '">
                            <textarea name="'.$name.'" id="'.$id.'" class="form-control" rows="'.$rows.'" placeholder="'.$placeholder.'">'.$value.'</textarea>
                        </div>
                    </div>';
        } else {
            $html = '<tr>
                        <td>'.$labelHtml.'</td>
                        <td>
                            <textarea name="'.$name.'" id="'.$id.'" class="form-control" rows="'.$rows.'" placeholder="'.$placeholder.'">'.$value.'</textarea>
                        </td>
                    </tr>';
        }
        return $html;
    }

    public function getSelectHorizontal($varName, $elements, $value, $size=6, $isBorderTop=True) {
        $html = '';
        $id = $varName;
        $name = $this->namePrefix . $varName;

        $labelText = $this->language->get('entry_'.$varName);
        $helpText = isset($this->langs['help_'.$id]) ? $this->langs['help_'.$id] : '';
        $helperText = isset($this->langs['helper_'.$id]) ? $this->langs['helper_'.$id] : '';
        $labelHtml = $this->getLabel($labelText, $name, $helpText, $helperText);

        $select = '<select name="'.$name.'" id="'.$id.'" class="form-control">';
        foreach ($elements as $eltKey => $eltName) {
            $selected = $eltKey == $value ? 'selected' : '';
            $select .= '<option value="'.$eltKey.'" '.$selected.'>'. $eltName.'</option>';
        }
        $select .= '</select>';

        $helpRightText = isset($this->langs['helpright_'.$id]) ? $this->langs['helpright_'.$id] : '';

        if(VERSION >= '2.0.0.0') {
            $lastsize = 12 - 3 - $size;
            $helpRightElt = $helpRightText ? '<div class="help-text">' . $helpRightText . '</div>' : '';
            $style = $isBorderTop ? '' : 'border-top: none;';
            $html .= '<div class="form-group" style="'.$style.'">
                    '.$labelHtml.'
                    <div class="col-sm-'. $size . '">
                        '.$select.'
                    </div>
                    <div class="col-sm-'. $lastsize . '">
                        '.$helpRightElt.'
                    </div>
                  </div>';
        } else {
            $helpRightElt = $helpRightText ? '<span class="help">' . $helpRightText . '</span>' : '';
            $html = '<tr>
                        <td>'.$labelHtml.'</td>
                        <td>
                            '.$select.'
                        </td>
                        <td>
                            '.$helpRightElt.'
                        </td>
                    </tr>';
        }
        return $html;
    }
}

