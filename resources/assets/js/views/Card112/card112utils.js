import moment from 'moment';

export const Card112Utils = {
    prepareModel(model, serviceTypes) {
        model.call_time = model.call_time ? moment(model.call_time).toDate() : moment().toDate();

        model.service_reactions = model.service_reactions
            ? this.prepareServiceReactions(model.service_reactions)
            : this.getEmptyServiceReactions(serviceTypes);

        model.chronology = model.chronology
            ? this.prepareChronology(model.chronology)
            : this.getEmptyChronology();

        return model;
    },
    getEmptyChronology() {
        let result = [];
        [0, 1, 2, 3, 4].map((i) => {
            result.push({
                id: i,
                time: moment().toDate(),
                comment: '',
                additional_comment: ''
            });
        });
        return result;
    },
    getEmptyServiceReactions(serviceTypes) {
        let result = [];
        serviceTypes.map((serviceType) => {
            result.push({
                id: null,
                service_type_id: serviceType.id,
                message_time: moment().toDate(),
                name: '',
                departure_time: moment().toDate(),
                arrival_time: moment().toDate()
            });
        });
        return result;
    },
    prepareServiceReactions(serviceReactions) {
        return serviceReactions.map(function (item) {
            item.message_time = moment(item.message_time, 'YYYY-MM-DD HH:mm:ss').toDate();
            item.departure_time = moment(item.departure_time, 'YYYY-MM-DD HH:mm:ss').toDate();
            item.arrival_time = moment(item.arrival_time, 'YYYY-MM-DD HH:mm:ss').toDate();
            return item;
        });
    },
    prepareChronology(chronology) {
        return chronology.map(function (item) {
            item.time = moment(item.time, 'YYYY-MM-DD HH:mm:ss').toDate();
            return item;
        });
    }
};
