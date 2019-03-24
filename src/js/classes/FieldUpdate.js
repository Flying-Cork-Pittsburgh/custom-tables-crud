import { debounce } from '../vendors/helpers/debounce';
import axios from 'axios'

class FieldUpdate {

	constructor (debug = false)
	{
		this._debug = debug
		if (this._debug) console.log('FieldUpdate init')

		if (this._setVars()) {
			if (this._debug) console.log('EServices vars init done')
			this._setEvents()
		}
	}

	_setVars()
	{
		this._getVars = []
		window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, (a, name, value) => { this._getVars[name]=value })
		// console.log('this._getVars', this._getVars)


		this._fieldsArr = [...document.querySelectorAll('[contenteditable="true"]')]
		if (!this._fieldsArr) return false

		// this._linksArr = [...this._eServicesWrapper.querySelectorAll('[data-eservices="link"]')]
		// if (!this._linksArr.length) return false

		// console.log(this._fieldsArr);


		return true
	}

	_setEvents()
	{
		this._fieldsArr.each((el) => {

			// add support for cut, copy and paste events too ?
			el.addEvent('input.FieldUpdate', debounce((e) => {
				this._onInputChange(e.target)
			}, 1500));
		})
	}

	_onInputChange(elem)
	{
		console.log('_onInputChange', this._getVars.page, elem.innerHTML, elem.getAttribute('data-rowid'), elem.getAttribute('data-colname'))

		this.loading = true
		let inputs = {}
		inputs.page		= this._getVars.page
		inputs.field	= elem.getAttribute('data-colname')
		inputs.id		= elem.getAttribute('data-rowid')
		inputs.value	= elem.innerHTML

		if (!window.ctcrud || !window.ctcrud.ajax_url) return


		axios.post(window.ctcrud.ajax_url, {
			inputs: inputs,
		// {
		// 	headers: {
		// 		'Content-Type': 'application/x-www-form-urlencoded',
		// 	}
		})
		.then(response => {
			this.loading = false
			console.log('OK ', response, response.data)

			if (response.data.success) {
				elem.addClass('update-ok')
			} else {
				elem.addClass('update-err')
			}
		})
		.catch(error => {
			this.loading = false
			console.log('ERR ', error.data)
			elem.addClass('update-err');
		})
	}

}

export { FieldUpdate }
