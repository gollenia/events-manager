type Props = {
	content: string;
	width: string;
};

const HtmlBlock = ( props: Props ) => {
	const { content, width } = props;
	const classes = [ 'core-block', 'grid__column--span-' + width ].join( ' ' );

	return (
		<div
			className={ classes }
			dangerouslySetInnerHTML={ { __html: content } }
		/>
	);
};

export default HtmlBlock;
