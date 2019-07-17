require('../lodash');
export default function (string, length = 40, separator = /[^A-Za-z0-9\.,]/) {
  return _.truncate(string, {length, separator});
};
