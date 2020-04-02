export const numCharLength = (val) => {
	console.log(val)
	return new Promise((resolve) => {
		let length = 12
		if(val !== null) {
			resolve(val.replace(/[^\d]/g, '').length === length)
		}
	}).then(v => {console.log(v); return v})
}