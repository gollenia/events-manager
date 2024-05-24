export type HTMLBlockProps = {
	content: string;
	width: number;
};

const HTMLBlock = ( props: HTMLBlockProps ) => {
	const { content, width } = props;
	const classes = [ 'ctx-form-field', 'core-block', 'ctx-form-field-w' + width ].join( ' ' );

	return <div className={classes} style={{
		gridColumn: `span ${width}`
	}} dangerouslySetInnerHTML={ { __html: content } } />;
};

export default HTMLBlock;
