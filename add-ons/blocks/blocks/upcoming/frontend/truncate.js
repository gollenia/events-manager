
/**
 * Truncate a text string to a number of words and add an ellipsis to it.
 * @param {string} text 
 * @param {int} maxWords 
 * @returns string 
 */
export default function truncate (text, maxWords, ellipsis = "...") {
	if (text.length == 0 || maxWords == 0) return '';
	const textArray = text.split(" ");
	if (textArray.length <= maxWords) return text;
	return textArray.splice(0, maxWords).join(" ") + ellipsis;
}