<?php

namespace Tagin\Controller;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Tagin\Controller;
use Tagin\Profiles;
use Tagin\WatchFunctions;

class RunController extends Controller
{
    const FILTER_ARGUMENT_NAME = 'filter';

    private $profiles;

    private $watches;

    public function __construct(App $app, Profiles $profiles, WatchFunctions $watches)
    {
        $this->app = $app;
        $this->container = $app->getContainer();
        $this->profiles = $profiles;
        $this->watches = $watches;
    }

    public function index(Request $request, Response $response)
    {
        $search = array();
        $keys = array('date_start', 'date_end', 'url');
        foreach ($keys as $key) {
            if ($request->getParam($key)) {
                $search[$key] = $request->getParam($key);
            }
        }
        $sort = $request->getParam('sort');

        $result = $this->profiles->getAll(array(
            'sort' => $sort,
            'page' => $request->getParam('page'),
            'direction' => $request->getParam('direction'),
            'perPage' => $this->container['config']['page.limit'],
            'conditions' => $search,
            'projection' => true,
        ));


        $title = 'Recent runs';
        $titleMap = array(
            'wt' => 'Longest wall time',
            'cpu' => 'Most CPU time',
            'mu' => 'Highest memory use',
        );
        if (isset($titleMap[$sort])) {
            $title = $titleMap[$sort];
        }

        $paging = array(
            'total_pages' => $result['totalPages'],
            'page' => $result['page'],
            'sort' => $sort,
            'direction' => $result['direction']
        );

        $this->_template = 'runs/list.twig';
        $this->set([
            'paging' => $paging,
            'base_url' => 'home',
            'runs' => $result['results'],
            'date_format' => $this->container['config']['date.format'],
            'search' => $search,
            'has_search' => strlen(implode('', $search)) > 0,
            'title' => $title
        ]);

        $this->render($response);
    }

    public function view(Request $request, Response $response, $args)
    {
        $detailCount = $this->container['config']['detail.count'];
        $result = $this->profiles->get($request->getParam('id'));

        $result->calculateSelf();

        // Self wall time graph
        $timeChart = $result->extractDimension('ewt', $detailCount);

        // Memory Block
        $memoryChart = $result->extractDimension('emu', $detailCount);

        // Watched Functions Block
        $watchedFunctions = array();
        foreach ($this->watches->getAll() as $watch) {
            $matches = $result->getWatched($watch['name']);
            if ($matches) {
                $watchedFunctions = array_merge($watchedFunctions, $matches);
            }
        }

        if (false !== $request->getParam(self::FILTER_ARGUMENT_NAME, false)) {
            $profile = $result->sort('ewt', $result->filter($result->getProfile(), $this->getFilters()));
        } else {
            $profile = $result->sort('ewt', $result->getProfile());
        }

        $this->_template = 'runs/view.twig';
        $this->set(array(
            'profile' => $profile,
            'result' => $result,
            'wall_time' => $timeChart,
            'memory' => $memoryChart,
            'watches' => $watchedFunctions,
            'date_format' => $this->config('date.format'),
        ));

        $this->render($response);
    }
    
    /**
     * @return array
     */
    protected function getFilters()
    {
        $request = $this->app->request();
        $filterString = $request->get(self::FILTER_ARGUMENT_NAME);
        if (strlen($filterString) > 1 && $filterString !== 'true') {
            $filters = array_map('trim', explode(',', $filterString));
        } else {
            $filters = $this->config('run.view.filter.names');
        }
        
        return $filters;
    }

    public function deleteForm(Request $request, Response $response)
    {
        $id = $request->getParam('id');

        if (!is_string($id) || !strlen($id)) {
            throw new Exception('The "id" parameter is required.');
        }

        // Get details
        $result = $this->profiles->get($id);

        $this->_template = 'runs/delete-form.twig';
        $this->set(array(
            'run_id' => $id,
            'result' => $result,
        ));

        $this->render($response);
    }

    public function deleteSubmit(Request $request, Response $response)
    {
        $id = $request->getParam('id');
        // Don't call profilers->delete() unless $id is set,
        // otherwise it will turn the null into a MongoId and return "Sucessful".
        if (!is_string($id) || !strlen($id)) {
            // Form checks this already,
            // only reachable by handcrafted or malformed requests.
            throw new \Exception('The "id" parameter is required.');
        }

        // Delete the profile run.
        $delete = $this->profiles->delete($id);

        //$this->app->flash('success', 'Deleted profile ' . $id);

        return $response->withRedirect('/');
    }

    public function deleteAllForm(Request $request, Response $response)
    {
        $this->_template = 'runs/delete-all-form.twig';

        $this->render($response);
    }

    public function deleteAllSubmit(Request $request, Response $response)
    {
        // Delete all profile runs.
        $delete = $this->profiles->truncate();

        //$this->app->flash('success', 'Deleted all profiles');

        return $response->withRedirect('/');
    }

    public function url(Request $request, Response $response)
    {
        $pagination = array(
            'sort' => $request->getParam('sort'),
            'direction' => $request->getParam('direction'),
            'page' => $request->getParam('page'),
            'perPage' => $this->config('page.limit'),
        );

        $search = array();
        $keys = array('date_start', 'date_end', 'limit', 'limit_custom');
        foreach ($keys as $key) {
            $search[$key] = $request->getParam($key);
        }

        $runs = $this->profiles->getForUrl(
            $request->getParam('url'),
            $pagination,
            $search
        );

        if (isset($search['limit_custom']) && strlen($search['limit_custom']) > 0 && $search['limit_custom'][0] == 'P') {
            $search['limit'] = $search['limit_custom'];
        }

        $chartData = $this->profiles->getPercentileForUrl(
            90,
            $request->getParam('url'),
            $search
        );

        $paging = array(
            'total_pages' => $runs['totalPages'],
            'sort' => $pagination['sort'],
            'page' => $runs['page'],
            'direction' => $runs['direction']
        );

        $this->_template = 'runs/url.twig';
        $this->set(array(
            'paging' => $paging,
            'base_url' => 'url.view',
            'runs' => $runs['results'],
            'url' => $request->getParam('url'),
            'chart_data' => $chartData,
            'date_format' => $this->config('date.format'),
            'search' => array_merge($search, array('url' => $request->getParam('url'))),
        ));

        $this->render($response);
    }

    public function compare(Request $request, Response $response)
    {
        $baseRun = $headRun = $candidates = $comparison = null;
        $paging = array();

        if ($request->getParam('base')) {
            $baseRun = $this->profiles->get($request->getParam('base'));
        }

        if ($baseRun && !$request->getParam('head')) {
            $pagination = array(
                'direction' => $request->getParam('direction'),
                'sort' => $request->getParam('sort'),
                'page' => $request->getParam('page'),
                'perPage' => $this->config('page.limit'),
            );
            $candidates = $this->profiles->getForUrl(
                $baseRun->getMeta('simple_url'),
                $pagination
            );

            $paging = array(
                'total_pages' => $candidates['totalPages'],
                'sort' => $pagination['sort'],
                'page' => $candidates['page'],
                'direction' => $candidates['direction']
            );
        }

        if ($request->getParam('head')) {
            $headRun = $this->profiles->get($request->getParam('head'));
        }

        if ($baseRun && $headRun) {
            $comparison = $baseRun->compare($headRun);
        }

        $this->_template = 'runs/compare.twig';
        $this->set(array(
            'base_url' => 'run.compare',
            'base_run' => $baseRun,
            'head_run' => $headRun,
            'candidates' => $candidates,
            'url_params' => $request->getParams(),
            'date_format' => $this->config('date.format'),
            'comparison' => $comparison,
            'paging' => $paging,
            'search' => array(
                'base' => $request->getParam('base'),
                'head' => $request->getParam('head'),
            )
        ));

        $this->render($response);
    }

    public function symbol(Request $request, Response $response)
    {
        $id = $request->getParam('id');
        $symbol = $request->getParam('symbol');

        $profile = $this->profiles->get($id);
        $profile->calculateSelf();
        list($parents, $current, $children) = $profile->getRelatives($symbol);

        $this->_template = 'runs/symbol.twig';
        $this->set(array(
            'symbol' => $symbol,
            'id' => $id,
            'main' => $profile->get('main()'),
            'parents' => $parents,
            'current' => $current,
            'children' => $children,
        ));

        $this->render($response);
    }

    public function symbolShort(Request $request, Response $response)
    {
        $id = $request->getParam('id');
        $threshold = $request->getParam('threshold');
        $symbol = $request->getParam('symbol');
        $metric = $request->getParam('metric');

        $profile = $this->profiles->get($id);
        $profile->calculateSelf();
        list($parents, $current, $children) = $profile->getRelatives($symbol, $metric, $threshold);

        $this->_template = 'runs/symbol-short.twig';
        $this->set(array(
            'symbol' => $symbol,
            'id' => $id,
            'main' => $profile->get('main()'),
            'parents' => $parents,
            'current' => $current,
            'children' => $children,
        ));

        $this->render($response);
    }

    public function callgraph(Request $request, Response $response)
    {
        $profile = $this->profiles->get($request->getParam('id'));

        $this->_template = 'runs/callgraph.twig';
        $this->set(array(
            'profile' => $profile,
            'date_format' => $this->config('date.format'),
        ));

        $this->render($response);
    }

    public function callgraphData(Request $request, Response $response)
    {
        $profile = $this->profiles->get($request->getParam('id'));
        $metric = $request->getParam('metric') ?: 'wt';
        $threshold = (float)$request->getParam('threshold') ?: 0.01;

        $callgraph = $profile->getCallgraph($metric, $threshold);

        return $response->withJson($callgraph);
    }

    public function callgraphDataDot(Request $request, Response $response)
    {
        $profile = $this->profiles->get($request->get('id'));
        $metric = $request->getParam('metric') ?: 'wt';
        $threshold = (float)$request->getParam('threshold') ?: 0.01;
        $callgraph = $profile->getCallgraphNodes($metric, $threshold);

        return $response->withJson($callgraph);
    }
}
