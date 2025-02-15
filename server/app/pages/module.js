var sparql = require('../common/queries/sparql');
var request = require('request');
var cors = require('cors');

const ServerUtils = require('../common/utils/server-utils.js');
const DatabusCache = require('../common/databus-cache');
const LayeredCache = require('../common/layered-cache')
const UriUtils = require('../common/utils/uri-utils');

module.exports = function (router, protector) {

  var cache = new LayeredCache(15, 6000);

  /* GET home page. */
  router.get('/', ServerUtils.HTML_ACCEPTED, protector.checkSso(), async function (req, res, next) {

    var indexCacheKey = 'index';

    var data = await cache.get(indexCacheKey, async function () {
      return {
        activityData: await sparql.pages.getGlobalActivityChartData(),
        rankingData: await sparql.pages.getPublishRankingData(),
        recentUploadsData: await sparql.pages.getRecentUploadsData()
      };
    });

    data.auth = ServerUtils.getAuthInfoFromRequest(req);
    res.render('index', { title: 'Databus', data: data });
  });


  // Pages login and logout
  router.get('/system/login', protector.protect(), function (req, res, next) {
    var redirectUrl = decodeURIComponent(req.query.redirectUrl);
    res.redirect(redirectUrl);
  });

  /*
    // Pages login and logout
    router.get('/page-login', protector.protect(), function (req, res, next) {
  
      console.log("HIER");
      var redirectUrl = decodeURIComponent(req.query.redirectUrl);
      res.redirect(redirectUrl);
    });*/

  router.get('/system/logout', function (req, res, next) {

    if (req.oidc.isAuthenticated()) {
      var requestUri = ServerUtils.getRequestUri(req);

      res.oidc.logout({
        returnTo: `${requestUri}/system/logout?redirectUrl=${req.query.redirectUrl}`
      });
    } else {
      res.redirect(decodeURIComponent(req.query.redirectUrl));
    }
  });

  

  router.get('/system/documentation/', protector.checkSso(), function (req, res, next) {


    var docuMd = require('./documentation.md');
    var accountShacl = require('../common/shacl/account-shacl.ttl');
    var groupShacl = require('../common/shacl/group-shacl.ttl');
    var dataidShacl = require('../common/shacl/dataid-shacl.ttl');
    var structureHtml = require('./documentation-structure.html');

    let options = {
      SHACL_SHAPE_ACCOUNT: accountShacl,
      SHACL_SHAPE_GROUP: groupShacl,
      SHACL_SHAPE_DATAID: dataidShacl,
      HTML_STRUCTURE: structureHtml,
    };

    for (var option in options) {
      var re = new RegExp('%' + option + '%', "g");
      docuMd = docuMd.replace(re, options[option]);
    }

    var uriReg = new RegExp('https://databus.example.org', "g");
    docuMd = docuMd.replace(uriReg, process.env.DATABUS_RESOURCE_BASE_URL);

    var auth = ServerUtils.getAuthInfoFromRequest(req);
    res.render('documentation', { title: 'Documentation', data: { auth: auth, content: docuMd } });
  });

  /* GET imprint page */
  router.get('/imprint', protector.checkSso(), function (req, res, next) {
    var auth = ServerUtils.getAuthInfoFromRequest(req);
    res.render('imprint', { title: 'Imprint', data: { auth: auth } });
  });

  /* GET about page */
  router.get('/about', protector.checkSso(), function (req, res, next) {
    var auth = ServerUtils.getAuthInfoFromRequest(req);
    res.render('about', { title: 'About', data: { auth: auth } });
  });


  var facetsCache = new DatabusCache(5);

  /**
   * Load facets for a given resource (group, artifact or version[to be implemented])
   * @param  {[type]}   req  [description]
   * @param  {[type]}   res  [description]
   * @param  {Function} next [description]
   * @return {[type]}        [description]
   */
  router.get('/system/pages/facets', async function (req, res, next) {

    try {

      var uri = req.query.uri;

      if (uri.endsWith("/")) {
        uri = uri.substr(0, uri.length - 1);
      }

      if (req.query.type == 'group') {
        var facets = await facetsCache.get(uri, async () => await sparql.pages.getGroupFacets(uri));
        res.status(200).send(facets);
        return;
      }

      if (req.query.type == 'artifact') {
        var facets = await facetsCache.get(uri, async () => await sparql.pages.getArtifactFacets(uri));
        res.status(200).send(facets);
        return;
      }

      if (req.query.type == 'version') {
        var facets = await facetsCache.get(uri, async () => await sparql.pages.getVersionFacets(uri));
        res.status(200).send(facets);
        return;
      }

      res.status(400).send('No resource type specified.');

    } catch (err) {
      console.log(err);
      res.status(500).send(err);
    }
  });


  router.get('/system/pages/artifacts-by-group', protector.protect(), async function (req, res, next) {

    try {
      var uri = req.query.uri;

      if(!UriUtils.isResourceUri(uri)) {
        res.status(400).send('Not a resource uri');
        return;
      }

      var splits = UriUtils.splitResourceUri(uri);

      console.log(splits);
      var result = await sparql.dataid.getArtifactsByGroup(splits[0], splits[1]);
      res.status(200).send(result);

    } catch (err) {
      console.log(err);
      res.status(500).send(err);
    }
  });



  require('./modules/account-page')(router, protector);
  require('./modules/collection-editor')(router, protector);
  require('./modules/publish-wizard')(router, protector);
  require('./modules/resource-pages')(router, protector);

}
