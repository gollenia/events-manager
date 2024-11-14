import { useEffect, useRef, useState } from '@wordpress/element';
import InputField from './InputField';
import useMediaQueries from './useMediaQueries';

type Response = {
	success: boolean;
	message: {
		html: string;
		attributes: {
			class: string;
		};
	};
};
type FormProps = {
	data: Array< any >;
	formUrl: string;
	onSubmit: ( event: any, data: any ) => void | null;
	onSubmissionFinished?: ( response: Response ) => void;
	validate: boolean;
	submitUrl: string;
	extraData?: any;
	onStateChange?: ( state: any ) => void;
	onChange?: ( form: any ) => void;
};

type FormState = 'LOADING' | 'LOADED' | 'SUBMITTING' | 'SUCCESS' | 'ERROR';

const Form = ( props: FormProps ) => {
	const { data, formUrl, onSubmit, submitUrl, onChange, onStateChange, extraData, onSubmissionFinished } = props;
	const [ status, setStatus ] = useState< FormState >( 'LOADING' );
	const [ fields, setFields ] = useState< Array< any > >( [] );
	const [ form, setForm ] = useState< { [ key: string ]: any } >( {} );
	const [ response, setResponse ] = useState< Response >();
	const [ touched, setTouched ] = useState( false );

	const { md, lg } = useMediaQueries();

	useEffect( () => {
		onStateChange?.( status );
	}, [ status ] );

	const formRef = useRef< HTMLFormElement >( null );

	useEffect( () => {
		if ( ! formUrl && data ) {
			setFields( data );
			setStatus( 'LOADED' );
			return;
		}
		fetch( formUrl )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				setFields( data.fields );

				setStatus( 'LOADED' );

				const fieldTemplate: any = {};
				data.fields.forEach( ( field: any ) => {
					fieldTemplate[ field.name ] = field.defaultValue;
				} );

				setForm( fieldTemplate );
			} );
	}, [ data, formUrl ] );

	if ( fields.length == 0 ) return <></>;

	const handleSubmit = ( event: any ) => {
		const formData = { ...form, ...extraData };

		event.preventDefault();
		if ( onSubmit ) {
			onSubmit( event, formData );
			return;
		}

		if ( ! ( formRef.current as HTMLFormElement | null )?.checkValidity() ) {
			if ( formRef.current && ( formRef.current as HTMLFormElement ).reportValidity ) {
				( formRef.current as HTMLFormElement ).reportValidity();
				return;
			}
		}

		if ( ! submitUrl ) {
			console.error( 'wp-react-form', 'No URL or onSubmit callback provided' );
			return;
		}

		if ( status == 'SUBMITTING' || status == 'SUCCESS' ) return;

		setStatus( 'SUBMITTING' );
		fetch( submitUrl, {
			method: 'POST',
			body: JSON.stringify( formData ),
			headers: {
				'Content-Type': 'application/json',
			},
		} )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				setStatus( data.success ? 'SUCCESS' : 'ERROR' );
				setResponse( data );
				onSubmissionFinished?.( data );
			} );
	};

	const resetForm = () => {
		setStatus( 'LOADED' );
		const fieldTemplate: any = {};
		fields.forEach( ( field: any ) => {
			fieldTemplate[ field.name ] = field.defaultValue;
		} );
		setForm( fieldTemplate );
	};

	if ( status == 'LOADING' ) return <></>;

	const onFormChange = ( event: any ) => {
		if ( onChange ) {
			onChange( form );
		}
	};

	const style: Object = {
		display: md ? 'grid' : 'flex',
		gridTemplateColumns: 'repeat(6, minmax(0, 1fr))',
		gridGap: '2rem',
		gap: '2rem',
		flexDirection: lg ? 'row' : 'column',
		position: 'relative',
		paddingTop: '1rem',
	};

	const classes = [
		'ctx-form',
		status == 'LOADED' ? 'ctx-form--loaded' : '',
		status == 'ERROR' ? 'ctx-form--error' : '',
		status == 'SUBMITTING' ? 'ctx-form--submitting' : '',
		status == 'SUCCESS' ? 'ctx-form--submitted' : '',
	].join( ' ' );

	return (
		<>
			<form
				className={ classes }
				ref={ formRef }
				style={ style }
				onSubmit={ handleSubmit }
				onInvalid={ ( event ) => {
					setTouched( true );
				} }
				onChange={ onFormChange }
				onReset={ resetForm }
			>
				{ status == 'ERROR' && ( // @ts-ignore
					<div
						className="ctx-form__response ctx-form__response--error"
						dangerouslySetInnerHTML={ {
							__html: response?.message?.html ?? '',
						} }
					></div>
				) }

				<div
					style={ {
						opacity: status == 'SUCCESS' ? 1 : 0,
						pointerEvents: status == 'SUCCESS' ? 'all' : 'none',
						transition: 'all 0.3s',
						left: 0,
						top: 0,
						bottom: 0,
						right: 0,
						position: 'absolute',
						zIndex: 5,
					} }
					className={ `ctx-form__response ${ status == 'SUCCESS' ? 'ctx-form__response--show' : '' }` }
					dangerouslySetInnerHTML={ {
						__html: response?.message?.html ?? '<p>Success</p>',
					} }
				></div>

				{ fields.map( ( field, index ) => {
					return (
						<InputField
							{ ...field }
							status={ status }
							disabled={ status == 'SUBMITTING' }
							formTouched={ touched }
							key={ index }
							value={ form[ field.name ] }
							onChange={ ( value: any ) => {
								setForm( ( fields ) => {
									return {
										...fields,
										[ field.name ]: value,
									};
								} );
							} }
						/>
					);
				} ) }
			</form>
		</>
	);
};

Form.defaultProps = {
	id: 0,
	lang: 'en',
	data: [],
	formUrl: '',
	onSubmit: null,
	validate: true,
	submitUrl: '',
};

export default Form;
