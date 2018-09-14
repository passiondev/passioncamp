<?php

// Rules for Laravel Code Standard
$rules = [
    // '@PSR2' => true,
    // 'array_syntax' => ['syntax' => 'short'],
    // 'binary_operator_spaces' => ['align_double_arrow' => false],
    // 'concat_space' => ['spacing' => 'one'],
    // 'linebreak_after_opening_tag' => true,
    // 'not_operator_with_successor_space' => true,
    // 'ordered_imports' => ['sortAlgorithm' => 'length'],
    // 'trailing_comma_in_multiline_array' => true,
    // 'no_unused_imports' => true,

    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => [
        'align_double_arrow' => null,
        'align_equals' => false,
    ],
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => [
        'statements' => ['return'],
    ],
    'cast_spaces' => true,
    // 'concat_space' => ['spacing' => 'none'],
    'concat_space' => ['spacing' => 'one'],
    'declare_equal_normalize' => true,
    'function_typehint_space' => true,
    'hash_to_slash_comment' => true,
    'heredoc_to_nowdoc' => true,
    'include' => true,
    'lowercase_cast' => true,
    'magic_constant_casing' => true,
    'method_argument_space' => true,
    'method_separation' => true,
    'native_function_casing' => true,
    'no_alias_functions' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_consecutive_blank_lines' => ['tokens' => [
        'extra',
        'use',
        'use_trait',
    ]],
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_mixed_echo_print' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_multiline_whitespace_before_semicolons' => true,
    'no_short_bool_cast' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_around_offset' => ['positions' => ['inside']],
    'no_trailing_comma_in_list_call' => true,
    'no_trailing_comma_in_singleline_array' => true,
    'no_unneeded_control_parentheses' => true,
    'no_unreachable_default_argument_value' => true,
    'no_unused_imports' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'normalize_index_brace' => true,
    'not_operator_with_successor_space' => true,
    'object_operator_without_whitespace' => true,
    'ordered_imports' => ['sortAlgorithm' => 'length'],
    // 'phpdoc_align' => [
    //     'parts' => [
    //         'hint',
    //     ],
    //     'separatorSpaces' => [
    //         'desc' => 2,
    //         'var' => 2,
    //     ],
    // ],
    // 'phpdoc_align' => true,
    // 'phpdoc_indent' => true,
    // 'phpdoc_inline_tag' => true,
    // 'phpdoc_no_access' => true,
    // 'phpdoc_no_alias_tag' => ['replacements' => [
    //     'type' => 'var',
    // ]],
    // 'phpdoc_no_package' => true,
    // 'phpdoc_no_useless_inheritdoc' => true,
    // 'phpdoc_scalar' => true,
    // 'phpdoc_single_line_var_spacing' => true,
    // 'phpdoc_summary' => true,
    // 'phpdoc_to_comment' => true,
    // 'phpdoc_trim' => true,
    // 'phpdoc_types' => true,
    // 'phpdoc_var_without_name' => true,
    'self_accessor' => true,
    'short_scalar_cast' => true,
    'simplified_null_return' => true,
    'single_blank_line_before_namespace' => true,
    'single_quote' => true,
    'space_after_semicolon' => true,
    'standardize_not_equals' => true,
    'ternary_operator_spaces' => true,
    'trailing_comma_in_multiline_array' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'whitespace_after_comma_in_array' => true,
];

$finder = PhpCsFixer\Finder::create()
    ->notPath('bootstrap/cache')
    ->notPath('storage')
    ->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setUsingCache(false)
    ->setFinder($finder);
