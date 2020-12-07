<?php declare(strict_types=1);
namespace Controller;

use \Psr\Http\Message\ResponseInterface;

class Page extends AbstractController
{
  /**
   * Export variables to the global scope of JS
   * @param array $data keys are names of variables
   * @return string javascript code
   */
  protected function exportToJs(array $data): string
  {
    $lines = [];
    foreach ($data as $key => $value) {
      $line = "window.$key = ";
      if (is_scalar($value)) {
        if (is_string($value)) {
          $line .= '"' . str_replace('"', '\"', $value) . '"';
        } else {
          $line .= $value;
        }
      } else {
        $line .= json_encode($value);
      }
      $lines[] = $line;
    }
    return implode(";\n", $lines);
  }




  /**
   * Build page from header, body and footer
   * @param string $body HTML placed between header and footer
   * @return ResponseInterface
   */
  protected function page($body): ResponseInterface
  {
    Header::addScript('/view/assets/grid.js');
    Header::addStyle('/view/assets/styles.css');
    Footer::addScript('/view/assets/app.js');

    return $this->textHtml(
      $this->loadController('Header')
      .$body
      .$this->loadController('Footer')
    );
  }




  public function home()
  {
    return $this->page($this->loadView('home'));
  }




  public function notFound()
  {
    return $this->page($this->loadView('404'));
  }
}
