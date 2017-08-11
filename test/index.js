var testsContext = require.context("./specs", true, /Test$/);
testsContext.keys().forEach(testsContext);