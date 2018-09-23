<?php

class MyValidator {
  private $_errors;

  public function __construct(string $encoding = 'UTF-8') {
    $_errors = [];
    mb_internal_encoding($encoding);
    $this->checkEncoding($_GET);
    $this->checkEncoding($_POST);
    $this->checkEncoding($_COOKIE);
    $this->checkNull($_GET);
    $this->checkNull($_POST);
    $this->checkNull($_COOKIE);
  }

  private function checkEncoding(array $data) {
    foreach($data as $key => $value) {
      if (!mb_check_encoding($value)) {
        $this->_errors[] = "{$key}は不正な文字コードです。";
      }
    }
  }

  private function checkNull(array $data) {
    foreach($data as $key => $value) {
      if (preg_match('/\0/', $value)) {
        $this->_errors[] = "{$key}は不正な文字を含んでいます。";
      }
    }
  }

  public function lengthCheck(string $value, string $name, int $len) {
    if (trim($value) !== '') {
      if (mb_strlen($value) > $len) {
        $this->_errors[] = "{$name}は{$len}文字以内で入力してください。";
      }
    }
  }

  public function regexCheck(string $value, string $name, string $pattern) {
    if (trim($value) !== '') {
      if (!preg_match($pattern, $value)) {
        $this->_errors[] = "{$name}は正しい形式で入力してください。";
      }
    }
  }

  public function __invoke() {
    if (count($this->_errors) > 0) {
      print '<ul style="color:Red">';
      foreach ($this->_errors as $err) {
        print "<li>{$err}</li>";
      }
      print '</ul>';
      die();
    }
  }
}
