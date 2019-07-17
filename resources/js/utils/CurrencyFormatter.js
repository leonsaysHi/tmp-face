require('../lodash');
import Decimal from 'decimal.js-light';

export default function (n, args = { maxValue: 1000000000000000, abs: true, round: false, precision: 2 }) {
  let
    value = new Decimal(n ? n : 0)
  if (args.abs) {
    value = value.abs()
  }
  if (args.maxValue && value.greaterThan(args.maxValue)) {
    return args.maxValue
  }
  if (!value.isInteger() && args.precision) {
    value =  value.toFixed(args.precision)
  }
  return value.toString()
};
