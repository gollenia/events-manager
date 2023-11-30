function formatPrice(price, locale = 'en-GB', currency = 'EUR') {
  const priceFormat = {
	style: 'currency',
	currency,
	minimumFractionDigits: 2
  };
  const priceFormatObject = new Intl.NumberFormat(locale, priceFormat);
  return priceFormatObject.format(price);
}