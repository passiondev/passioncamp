<script>
import { flatMapDeep } from 'lodash'

export default {
    props: [ 'name', 'value' ],

    methods: {
        flatInputsFromDeepJson(item, key, h) {
            if (typeof item === 'object') {
                return flatMapDeep(item, (value, newKey) => {
                    return this.flatInputsFromDeepJson(value, key.concat(newKey), h)
                })
            }

            return h('input', { attrs: {
                class: 'hidden',
                type: 'hidden',
                name: this.name ? this.name + this.wrapInBrackets(key) : key,
                value: item,
            }})
        },

        wrapInBrackets(keys) {
            return keys.length
                ? '[' + Array.from(keys).join('][') + ']'
                : ''
        }
    },

    render(h) {
        return h('div', [
            this.flatInputsFromDeepJson(this.value, [], h)
        ])
    },
}
</script>
