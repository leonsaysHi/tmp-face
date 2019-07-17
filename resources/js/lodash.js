window._ = require('lodash');

_.mixin({
  isEqualIgnoreCase(value, other) {
    if(!_.isString(value) || !_.isString(other)) {
      return _.isEqual(value, other);
    }
    return _.isEqual(_.toLower(value), _.toLower(other)); 
  },
  includesIgnoreCase(value, other) {
    return _.includes(_.toLower(value), _.toLower(other));    
  },
  sortWith(array, comparator) {
    return _.map(array).sort(comparator);
  },
  queryToObject(query) {
    if(!query) return {};
    return _.chain(query.substring(1))
      .split('&')
      .map(item => _.split(item, '='))
      .fromPairs()
      .value();
  }
});
