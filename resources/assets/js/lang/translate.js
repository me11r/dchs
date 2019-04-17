import ruTranslations from './ru.js';
import kkTranslations from './kk.js';

const translations = {
    'ru': ruTranslations,
    'kk': kkTranslations
};

class Translate {

    get(code, lang) {
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
}

export default Translate;