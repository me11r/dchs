import moment from 'moment';

export const Card102utils = {
    prepareModel(model, serviceTypes) {
        // model.call_time = model.call_time ? moment(model.call_time).toDate() : moment().toDate();
        model.chronology_start_time = model.chronology_start_time ? moment(model.chronology_start_time).toDate() : moment().toDate();
        model.chronology_end_time = model.chronology_end_time ? moment(model.chronology_end_time).toDate() : moment().toDate();

        /*model.service_reactions = model.service_reactions.length > 0
            ? this.prepareServiceReactions(model.service_reactions)
            : this.getEmptyServiceReactions(serviceTypes);

        model.chronology = model.chronology.length > 0
            ? this.prepareChronology(model.chronology)
            : [this.getEmptyChronologyItem()];*/

        return model;
    },
    getEmptyChronologyItem() {
        return {
            id: moment().valueOf(),
            time: moment().hour(0).minute(0).toDate(),
            comment: ''
        };
    },
    getEmptyServiceReactions(serviceTypes) {
        let result = [];
        serviceTypes.map((serviceType) => {
            result.push({
                id: null,
                service_type_id: serviceType.id,
                message_time: moment().hour(0).minute(0).toDate(),
                name: '',
                departure_time: moment().hour(0).minute(0).toDate(),
                arrival_time: moment().hour(0).minute(0).toDate()
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
