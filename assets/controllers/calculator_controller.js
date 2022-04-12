import {Controller} from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = ["display", "output"];

    clearFlag = false;
    messages = ['ERROR', 'NAN'];
    allowedKeys = ['Control', 'Shift', 'Tab', 'ArrowRight','ArrowLeft','ArrowUp','ArrowDown']
    operands = ['+', '-', '/', '*'];
    apiUrl = null;

    /**
     * Initializes the display and sets the API url
     */
    connect() {
        this.displayValue(0);
        this.apiUrl = this.element.parentElement.getAttribute('action');
    }


    /**
     * Adds one of the operands to the query
     *
     * @param event
     */
    addOperand(event) {
        event.preventDefault();
        let currentOperation = this.displayTarget.value;
        if (this.clearFlag) {
            this.clearFlag = false;
        }
        currentOperation = this.prepareDisplayInput(this.displayTarget.value);

        if (currentOperation !== '0' && currentOperation !== '') {
            currentOperation = currentOperation.replace(/[\-+*\/]$/, '');
            currentOperation = this.appendCharacterToDisplayValue(currentOperation, event.target.getAttribute('data-actionValue').toString());
        }
        this.displayValue(currentOperation);
    }

    /**
     * Callback function for the = button
     *
     * @param event
     */
    result(event) {
        event.preventDefault();
        this.postData().then(response => {
            let operation = this.messages[0];
            if (response.status === true) {
                operation = response.response.toString();
            } else {
                console.error(response.response);
            }
            this.displayValue(operation);
            this.clearFlag = true;
        })
    }

    /**
     * Append a new character (number or operand) to the end of the input string
     *
     * @param currentOperation
     * @param newCharacter
     * @returns {string}
     */
    appendCharacterToDisplayValue(currentOperation, newCharacter) {
        return currentOperation.toString() + newCharacter.toString();
    }

    /**
     * Callback function for one of the main calculator buttons
     *
     * @param event
     */
    addNumber(event) {
        event.preventDefault();
        let currentOperation = this.prepareDisplayInput(this.displayTarget.value);

        currentOperation = this.appendCharacterToDisplayValue(currentOperation, event.target.value.toString());
        this.displayValue(currentOperation);
    }

    /**
     * This will clear the input if there's a clear flag, the value is 0 or an error message
     *
     * @param currentOperation
     * @returns {string}
     */
    prepareDisplayInput(currentOperation) {
        if (this.clearFlag || currentOperation === '0' || this.messages.includes(currentOperation)) {
            currentOperation = '';
            this.clearFlag = false;
        }
        return currentOperation;
    }

    /**
     * Show a new Value on the display
     *
     * @param newDisplayValue
     */
    displayValue(newDisplayValue) {
        this.displayTarget.value = newDisplayValue.toString();
    }

    /**
     * Async function to post data to the API
     *
     * @returns {Promise<any>}
     */
    async postData() {
        // Default options are marked with *
        const response = await fetch(this.apiUrl, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'no-referrer',
            body: JSON.stringify({'calculation': this.displayTarget.value})
        });
        return response.json(); // parses JSON response into native JavaScript objects
    }

    /**
     * Checks the manual input from the keyboard event
     * If it's not a number or operator or any allowed keys stops propagation
     *
     * @param event
     */
    checkManualInput(event) {
        console.log(event.key);
        if (!this.isValidKey(event)) {
            event.preventDefault();
        }
        if (event.key === 'Enter' || event.key === '=') {
            this.result(event);
        }
    }

    /**
     * Checks if the event key is within the allowed parameters
     *
     * @param event
     * @returns {boolean}
     */
    isValidKey(event) {
        if (event.ctrlKey && ['a', 'c', 'x'].includes(event.key)) {
            return true;
        }

        if (this.allowedKeys.includes(event.key)) {
            return true;
        }

        return /[0-9.\/\*\-\+]/.test(event.key);
    }
}
