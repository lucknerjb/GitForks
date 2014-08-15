<?php

/**
*
*/
class RepositoriesController extends Controller
{

  private function sort_repositories($array) {}

  public function index($user, $repository) {
    // Store the search in the db

    $response = GitHub::getHttpClient()->get("repos/{$user}/{$repository}/forks?per_page=100&sort=stargazers&order=desc");
    $repos    = Github\HttpClient\Message\ResponseMediator::getContent($response);

    $data = array();

    foreach($repos as $repo) {
      $tmp = array(
        'id' => $repo['owner']['id'],
        'avatar_url' => $repo['owner']['avatar_url'],
        'owner' => $repo['owner']['login'],
        'owner_id' => $repo['id'],
        'full_name' => $repo['full_name'],
        'description' => $repo['description'],
        'created' => $repo['created_at'],
        'stars' => $repo['stargazers_count'],
        'watchers' => $repo['watchers'],
        'forks' => $repo['forks'],
        'open_issues' => $repo['open_issues']
      );

      // @TODO: In V2, show commit count in the past say 6-12 months and possible show as a graph on a per week basis
      // Get commits
      // $year_ago_today = date('c', strtotime('-3 years', strtotime(date('Y-m-d')))); // "c" format gives the date in ISO 8601, needed by github
      // echo "/repos/{$tmp['full_name']}/commits?since={$year_ago_today}";
      // $commits_response = GitHub::getHttpClient()->get("/repos/{$tmp['full_name']}/commits?since={$year_ago_today}");
      // $commits_response = GitHub::getHttpClient()->get("/repos/{$tmp['full_name']}/stats/commit_activity");
      // $commits = Github\HttpClient\Message\ResponseMediator::getContent($commits_response);
      // print_r($commits); die;

      $data[] = $tmp;
    }

    function cmp($a, $b) {
      if ($a == $b) { return 0; }
      return ($a['stars'] < $b['stars']) ? +1 : -1;
    };

    usort($data, 'cmp');

    // echo json_encode($data); die;

    $view = View::make('repositories.index')
      ->with('data', $data);

    return $view;
  }
}
