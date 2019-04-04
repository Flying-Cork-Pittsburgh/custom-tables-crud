// import { FieldUpdate } from './FieldUpdate'

class Core {
	constructor() {
		// console.log('running JS core..')
		this._run();
	}

	_run() {
		// new FieldUpdate()
	}
}

const ready = fn => {
	if (document.attachEvent ?
			document.readyState === 'complete' :
			document.readyState !== 'loading'
	) {
		fn();
	} else {
		document.addEventListener('DOMContentLoaded', fn );
	}
};

ready(() => {
	new Core();
});
