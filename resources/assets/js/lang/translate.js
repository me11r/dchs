import ruTranslations from './ru.js';
import kkTranslations from './kk.js';

const translations = {
    'ru': ruTranslations,
    'kk': kkTranslations
};

class Translate {

    find(code, lang) {
        if (translations[lang] === undefined) return code;

        let object = translations[lang];

        if (object !== undefined) {
            let translation = object[code];
            if (translation !== undefined) {
                return translation;
            } else {
                return code;
            }
        } else {
            return code;
        }
    }

    //old
    findInWindow (key) {
        if (window.translations === undefined) return key;

        let result = window.translations.find((item) => {
            return item.key === key;
        });

        return result !== undefined ? result.value : key;
    }

    // sample of  using
    // {{ '/reports/analytics-spiasr.water_consumption.title' | trans({date_from:'abc', date_to: 'efg'}) }}
    get(code, replace) {

        let translate = this.findInWindow(code);

        if (replace !== undefined && translate) {
            for (let key in replace) {
                translate = translate.replace(`:${key}`, replace[key]);
            }
        }

        return translate;
    }

    getLocaleFromUrl () {
        let urlSegments = window.location.href.split('/');
        let kk = urlSegments.find((segment) => {
            return segment === 'kk';
        });

        if (kk !== undefined) {
            window.localStorage.setItem('language', 'kk');
            return 'kk';
        }

        window.localStorage.setItem('language', 'ru');

        return 'ru';
    }
}

export default Translate;