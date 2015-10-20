<?php
namespace OCFram;
 
class Router
{
  /** @var Route[] $routes */
  protected $routes = [];
 
  const NO_ROUTE = 1;

  /**
   * @param Route $route
   */
  public function addRoute(Route $route)
  {
    if (!in_array($route, $this->routes))
    {
      $this->routes[$route->module().'.'.$route->action()] = $route;
    }
  }

  /**
   * @param $url
   * @return Route
   */
  public function getRoute($url)
  {
    foreach ($this->routes as $route)
    {
      // Si la route correspond à l'URL
      if (($varsValues = $route->match($url)) !== false)
      {
        // Si elle a des variables
        if ($route->hasVars())
        {
          $varsNames = $route->varsNames();
          $listVars = [];
 
          // On crée un nouveau tableau clé/valeur
          // (clé = nom de la variable, valeur = sa valeur)
          foreach ($varsValues as $key => $match)
          {
            // La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match)
            if ($key !== 0)
            {
              $listVars[$varsNames[$key - 1]] = $match;
            }
          }
 
          // On assigne ce tableau de variables � la route
          $route->setVars($listVars);
        }
 
        return $route;
      }
    }
 
    throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
  }

  /**
   * @param $module
   * @param $action
   * @param array|null $varsNamesValues
   * @return string|void
   */
  public function getURL($module,$action,array $varsNamesValues=null)
  {
      if (!isset($this->routes[$module.'.'.$action]))
      {
        throw new \RuntimeException('Aucune route ne correspond au module et à l\'action passés en paramètres', self::NO_ROUTE);
      }
      $Route = $this->routes[$module.'.'.$action];
      $url = $Route->url();
      if ($Route->hasVars())
      {
        foreach($Route->varsNames() as $name)
        {
          if(empty($varsNamesValues[$name]))
          {
            throw new \RuntimeException('Erreur : nom variable non correct pour la varName : '.$name.'!');
          }
          $url = preg_replace(array('([0-9]+)\\','([a-z]+)\\'),$varsNamesValues[$name],$url,1);//a modif
          unset($varsNamesValues[$name]);
        }
      }
      if (sizeof($varsNamesValues) > 0)
        throw new \RuntimeException('Erreur : Trop de variables passées à getURL !');
      return $url;

  }
}