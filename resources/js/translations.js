module.exports = {
    methods: {
        __(key, replace) {
            translation = window._translations[key]
                ? window._translations[key]
                : string_key

            _.forEach(replace, (value, key) => {
                translation = translation.replace(':' + key, value)
            })
            return translation
        },
        _months() {
            if(_lang == 'en') {
                return [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December ",
                  ];
            }
            return [
                "Styczeń",
                "Luty",
                "Marzec",
                "Kwiecień",
                "Maj",
                "Czerwiec",
                "Lipiec",
                "Sierpień",
                "Wrzesień",
                "Październik",
                "Listopad",
                "Grudzień ",
              ];
        }
    },
}