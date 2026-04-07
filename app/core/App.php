<?php
class App
{
  protected $controller = 'Home';
  protected $method = 'index';
  protected $params = [];

  public function __construct()
  {
    $url = $this->getUrl();

    // =========================
    // 1. CONTROLLER
    // =========================
    if (isset($url[0]) && $url[0] !== '') {
      $controllerName = ucfirst($url[0]);
      $controllerPath = '../app/controllers/' . $controllerName . '.php';

      if (file_exists($controllerPath)) {
        $this->controller = $controllerName;
        unset($url[0]);
      }
    }

    require_once '../app/controllers/' . $this->controller . '.php';
    $this->controller = new $this->controller();

    // =========================
    // 2. METHOD
    // =========================
    if (isset($url[1]) && $url[1] !== '') {
      if (method_exists($this->controller, $url[1])) {
        $this->method = $url[1];
        unset($url[1]);
      } else {
        $this->error("Method '{$url[1]}' tidak ditemukan");
      }
    }

    // =========================
    // 3. PARAMS
    // =========================
    $this->params = !empty($url) ? array_values($url) : [];

    // =========================
    // 4. EXECUTE
    // =========================
    call_user_func_array([$this->controller, $this->method], $this->params);
  }

  // =========================
  // URL PARSER
  // =========================
  private function getUrl()
  {
    if (!isset($_GET['url'])) {
      return [];
    }

    $url = rtrim($_GET['url'], '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);

    return explode('/', $url);
  }

  // =========================
  // ERROR HANDLER
  // =========================
  private function error($message)
  {
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "<p>$message</p>";
    die();
  }
}
