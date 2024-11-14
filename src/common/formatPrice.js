const getBrowserLanguage = () => {
	if ( navigator.languages !== undefined ) {
		return navigator.languages[ 0 ];
	}
	return navigator.language;
};

const formatPrice = ( value, currency = 'USD' ) => {
	const lang = getBrowserLanguage();
	return new Intl.NumberFormat( lang, {
		style: 'currency',
		currency: currency,
	} ).format( value );
};

export { formatPrice };
