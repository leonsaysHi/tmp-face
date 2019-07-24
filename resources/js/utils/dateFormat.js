export default function (
  date = moment().toDate(),
  format = 'DD/MMMM/YYYY',
  defaultValue = '- -'
) {
  return date ? moment(date).format(format) : defaultValue;
};
