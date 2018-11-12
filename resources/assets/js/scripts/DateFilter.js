import moment from 'moment';
export default function(value, format) {
    return moment(String(value)).format(format);
}
