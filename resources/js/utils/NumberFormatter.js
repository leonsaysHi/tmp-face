require('../lodash');
import Decimal from 'decimal.js-light';

export default function (n, args = {}) {
  const
    maxValue = 1000000000000000,
    value = n ? new Decimal(n).abs().toInteger() : 0;
  return (value > maxValue) ? maxValue : value.toString();
};
