<?php
    Class Form extends Zerdardian {
        // Form info
        private $tokenid;
        private $formid;
        public $formname;
        public $formtype;

        public $formdata;
        public $error;
        protected $i = 0;

        // Functions

        function CreateForm(string $formname) { 
            $this->tokenid = uniqid('form_');
            $this->formname = $formname;
        }

        function formString(string $name, int $length = null, bool $required = false, string $placeholder = null, string $toptext = null, bool $disabled = false) {
            $this->formdata[$this->i]['type'] = 'text';
            $this->formdata[$this->i]['name'] = $name;
            $this->formdata[$this->i]['length'] = $length;
            $this->formdata[$this->i]['required'] = $required;
            $this->formdata[$this->i]['placeholder'] = $placeholder;
            $this->formdata[$this->i]['toptext'] = $toptext;
            $this->formdata[$this->i]['disabled'] = $disabled;
            $this->i++;
        }
        
        function formNumber(string $name, int $length = null, bool $required = false, string $placeholder = null, string $toptext = null, bool $disabled = false) {
            $this->formdata[$this->i]['type'] = 'number';
            $this->formdata[$this->i]['name'] = $name;
            $this->formdata[$this->i]['length'] = $length;
            $this->formdata[$this->i]['required'] = $required;
            $this->formdata[$this->i]['placeholder'] = $placeholder;
            $this->formdata[$this->i]['toptext'] = $toptext;
            $this->formdata[$this->i]['disabled'] = $disabled;
            $this->i++;
        }

        function formPassword(string $name, int $length = null, bool $required = false, string $placeholder = null, string $toptext = null, bool $disabled = false) {
            $this->formdata[$this->i]['type'] = 'password';
            $this->formdata[$this->i]['name'] = $name;
            $this->formdata[$this->i]['length'] = $length;
            $this->formdata[$this->i]['required'] = $required;
            $this->formdata[$this->i]['placeholder'] = $placeholder;
            $this->formdata[$this->i]['disabled'] = $disabled;
            $this->formdata[$this->i]['toptext'] = $toptext;
            $this->formdata[$this->i]['disabled'] = $disabled;
            $this->i++;
        }

        function formSelect(string $name, array $array, bool $required = false, string $toptext = null, bool $disabled = false) {
            $this->formdata[$this->i]['type'] = 'select';
            $this->formdata[$this->i]['name'] = $name;
            $this->formdata[$this->i]['required'] = $required;
            $opt = 0;
            foreach($array as $option) {
                $this->formdata[$this->i]['options'][$opt] = $option;
                $opt++;
            }
            $this->formdata[$this->i]['toptext'] = $toptext;
            $this->formdata[$this->i]['disabled'] = $disabled;
            $this->i++;
        }

        function formDate(string $name, bool $required = false, string $toptext = null, bool $disabled = false) {
            $this->formdata[$this->i]['type'] = 'date';
            $this->formdata[$this->i]['name'] = $name;
            $this->formdata[$this->i]['required'] = $required;
            $this->formdata[$this->i]['toptext'] = $toptext;
            $this->formdata[$this->i]['disabled'] = $disabled;
            $this->i++;
        }

        function formSubmit(string $name, string $custom = null, bool $disabled = false) {
            $this->formdata[$this->i]['type'] = 'submit';
            $this->formdata[$this->i]['name'] = $name;
            $this->formdata[$this->i]['custom'] = $custom;
            $this->formdata[$this->i]['disabled'] = $disabled;
            $this->i++;
        }

        function buildForm() {
            $form = "<form action='".$this->url."' method='POST'>";
            $form.= "<input type='hidden' name='tokenid' value='".$this->tokenid."'>";
            foreach($this->formdata as $data) {
                $form.="<div id='".$this->formname."_".$data['name']."' class='".$data['name']."'>";
                if(!empty($data['toptext']) && $data['toptext'] != null) $form.="<label for='".$data['name']."'>".$data['toptext']."</label>";
                $disabled = '';
                if($data['disabled'] == true) $disabled = 'disabled';
                switch($data['type']) {
                    case 'text':
                        $form.="<input type='text' name='".$data['name']."' id='".$data['name']."' class='form textform text' placeholder='".$data['placeholder']."' $disabled>";
                        break;
                    case 'password':
                        $form.="<input type='password' name='".$data['name']."' id='".$data['name']."' class='form textform password' placeholder='".$data['placeholder']."' $disabled>";
                        break;
                    case 'date':
                        $form.="<input type='date' name='".$data['name']."' id='".$data['name']."' class='form dateform date'>";
                        break;
                    case 'submit':
                        $value = '';
                        if(!empty($data['custom'])) $value = "value='".$data['custom']."'";
                        $form.="<input type='submit' name='".$data['name']."' id='".$data['name']."' $value class='form button submit' $disabled>";
                        break;
                }
                $form.="</div>";
            }
            $form.= "</form>";

            return $form;
        }

        function returnData() {
            return $this->formdata;
        }
    }
