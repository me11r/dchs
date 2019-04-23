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

    getReplace(code, lang, replace) {

        let translate = this.get(code, lang);

        if (replace.length > 0 && translate) {
            for (let key in replace) {
                translate = translate.replace(`:${key}`, replace[key]);
            }

            return translate;
        }

        return null;
    }
}

export default Translate;