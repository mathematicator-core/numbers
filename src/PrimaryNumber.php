<?php

declare(strict_types=1);

namespace Mathematicator\Numbers;


use Generator;

final class PrimaryNumber
{
	/**
	 * It gradually returns a list of all small prime numbers.
	 *
	 * @return Generator<int>
	 */
	public static function getList(): Generator
	{
		$numbers = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97, 101, 103, 107, 109, 113, 127, 131, 137, 139, 149, 151, 157, 163, 167, 173, 179, 181, 191, 193, 197, 199, 211, 223, 227, 229, 233, 239, 241, 251, 257, 263, 269, 271, 277, 281, 283, 293, 307, 311, 313, 317, 331, 337, 347, 349, 353, 359, 367, 373, 379, 383, 389, 397, 401, 409, 419, 421, 431, 433, 439, 443, 449, 457, 461, 463, 467, 479, 487, 491, 499, 503, 509, 521, 523, 541, 547, 557, 563, 569, 571, 577, 587, 593, 599, 601, 607, 613, 617, 619, 631, 641, 643, 647, 653, 659, 661, 673, 677, 683, 691, 701, 709, 719, 727, 733, 739, 743, 751, 757, 761, 769, 773, 787, 797, 809, 811, 821, 823, 827, 829, 839, 853, 857, 859, 863, 877, 881, 883, 887, 907, 911, 919, 929, 937, 941, 947, 953, 967, 971, 977, 983, 991, 997, 1_009, 1_013, 1_019, 1_021, 1_031, 1_033, 1_039, 1_049, 1_051, 1_061, 1_063, 1_069, 1_087, 1_091, 1_093, 1_097, 1_103, 1_109, 1_117, 1_123, 1_129, 1_151, 1_153, 1_163, 1_171, 1_181, 1_187, 1_193, 1_201, 1_213, 1_217, 1_223, 1_229, 1_231, 1_237, 1_249, 1_259, 1_277, 1_279, 1_283, 1_289, 1_291, 1_297, 1_301, 1_303, 1_307, 1_319, 1_321, 1_327, 1_361, 1_367, 1_373, 1_381, 1_399, 1_409, 1_423, 1_427, 1_429, 1_433, 1_439, 1_447, 1_451, 1_453, 1_459, 1_471, 1_481, 1_483, 1_487, 1_489, 1_493, 1_499, 1_511, 1_523, 1_531, 1_543, 1_549, 1_553, 1_559, 1_567, 1_571, 1_579, 1_583, 1_597, 1_601, 1_607, 1_609, 1_613, 1_619, 1_621, 1_627, 1_637, 1_657, 1_663, 1_667, 1_669, 1_693, 1_697, 1_699, 1_709, 1_721, 1_723, 1_733, 1_741, 1_747, 1_753, 1_759, 1_777, 1_783, 1_787, 1_789, 1_801, 1_811, 1_823, 1_831, 1_847, 1_861, 1_867, 1_871, 1_873, 1_877, 1_879, 1_889, 1_901, 1_907, 1_913, 1_931, 1_933, 1_949, 1_951, 1_973, 1_979, 1_987, 1_993, 1_997, 1_999, 2_003, 2_011, 2_017, 2_027, 2_029, 2_039, 2_053, 2_063, 2_069, 2_081, 2_083, 2_087, 2_089, 2_099, 2_111, 2_113, 2_129, 2_131, 2_137, 2_141, 2_143, 2_153, 2_161, 2_179, 2_203, 2_207, 2_213, 2_221, 2_237, 2_239, 2_243, 2_251, 2_267, 2_269, 2_273, 2_281, 2_287, 2_293, 2_297, 2_309, 2_311, 2_333, 2_339, 2_341, 2_347, 2_351, 2_357, 2_371, 2_377, 2_381, 2_383, 2_389, 2_393, 2_399, 2_411, 2_417, 2_423, 2_437, 2_441, 2_447, 2_459, 2_467, 2_473, 2_477, 2_503, 2_521, 2_531, 2_539, 2_543, 2_549, 2_551, 2_557, 2_579, 2_591, 2_593, 2_609, 2_617, 2_621, 2_633, 2_647, 2_657, 2_659, 2_663, 2_671, 2_677, 2_683, 2_687, 2_689, 2_693, 2_699, 2_707, 2_711, 2_713, 2_719, 2_729, 2_731, 2_741, 2_749, 2_753, 2_767, 2_777, 2_789, 2_791, 2_797, 2_801, 2_803, 2_819, 2_833, 2_837, 2_843, 2_851, 2_857, 2_861, 2_879, 2_887, 2_897, 2_903, 2_909, 2_917, 2_927, 2_939, 2_953, 2_957, 2_963, 2_969, 2_971, 2_999, 3_001, 3_011, 3_019, 3_023, 3_037, 3_041, 3_049, 3_061, 3_067, 3_079, 3_083, 3_089, 3_109, 3_119, 3_121, 3_137, 3_163, 3_167, 3_169, 3_181, 3_187, 3_191, 3_203, 3_209, 3_217, 3_221, 3_229, 3_251, 3_253, 3_257, 3_259, 3_271, 3_299, 3_301, 3_307, 3_313, 3_319, 3_323, 3_329, 3_331, 3_343, 3_347, 3_359, 3_361, 3_371, 3_373, 3_389, 3_391, 3_407, 3_413, 3_433, 3_449, 3_457, 3_461, 3_463, 3_467, 3_469, 3_491, 3_499, 3_511, 3_517, 3_527, 3_529, 3_533, 3_539, 3_541, 3_547, 3_557, 3_559, 3_571, 3_581, 3_583, 3_593, 3_607, 3_613, 3_617, 3_623, 3_631, 3_637, 3_643, 3_659, 3_671, 3_673, 3_677, 3_691, 3_697, 3_701, 3_709, 3_719, 3_727, 3_733, 3_739, 3_761, 3_767, 3_769, 3_779, 3_793, 3_797, 3_803, 3_821, 3_823, 3_833, 3_847, 3_851, 3_853, 3_863, 3_877, 3_881, 3_889, 3_907, 3_911, 3_917, 3_919, 3_923, 3_929, 3_931, 3_943, 3_947, 3_967, 3_989, 4_001, 4_003, 4_007, 4_013, 4_019, 4_021, 4_027, 4_049, 4_051, 4_057, 4_073, 4_079, 4_091, 4_093, 4_099, 4_111, 4_127, 4_129, 4_133, 4_139, 4_153, 4_157, 4_159, 4_177, 4_201, 4_211, 4_217, 4_219, 4_229, 4_231, 4_241, 4_243, 4_253, 4_259, 4_261, 4_271, 4_273, 4_283, 4_289, 4_297, 4_327, 4_337, 4_339, 4_349, 4_357, 4_363, 4_373, 4_391, 4_397, 4_409, 4_421, 4_423, 4_441, 4_447, 4_451, 4_457, 4_463, 4_481, 4_483, 4_493, 4_507, 4_513, 4_517, 4_519, 4_523, 4_547, 4_549, 4_561, 4_567, 4_583, 4_591, 4_597, 4_603, 4_621, 4_637, 4_639, 4_643, 4_649, 4_651, 4_657, 4_663, 4_673, 4_679, 4_691, 4_703, 4_721, 4_723, 4_729, 4_733, 4_751, 4_759, 4_783, 4_787, 4_789, 4_793, 4_799, 4_801, 4_813, 4_817, 4_831, 4_861, 4_871, 4_877, 4_889, 4_903, 4_909, 4_919, 4_931, 4_933, 4_937, 4_943, 4_951, 4_957, 4_967, 4_969, 4_973, 4_987, 4_993, 4_999, 5_003, 5_009, 5_011, 5_021, 5_023, 5_039, 5_051, 5_059, 5_077, 5_081, 5_087, 5_099, 5_101, 5_107, 5_113, 5_119, 5_147, 5_153, 5_167, 5_171, 5_179, 5_189, 5_197, 5_209, 5_227, 5_231, 5_233, 5_237, 5_261, 5_273, 5_279, 5_281, 5_297, 5_303, 5_309, 5_323, 5_333, 5_347, 5_351, 5_381, 5_387, 5_393, 5_399, 5_407, 5_413, 5_417, 5_419, 5_431, 5_437, 5_441, 5_443, 5_449, 5_471, 5_477, 5_479, 5_483, 5_501, 5_503, 5_507, 5_519, 5_521, 5_527, 5_531, 5_557, 5_563, 5_569, 5_573, 5_581, 5_591, 5_623, 5_639, 5_641, 5_647, 5_651, 5_653, 5_657, 5_659, 5_669, 5_683, 5_689, 5_693, 5_701, 5_711, 5_717, 5_737, 5_741, 5_743, 5_749, 5_779, 5_783, 5_791, 5_801, 5_807, 5_813, 5_821, 5_827, 5_839, 5_843, 5_849, 5_851, 5_857, 5_861, 5_867, 5_869, 5_879, 5_881, 5_897, 5_903, 5_923, 5_927, 5_939, 5_953, 5_981, 5_987, 6_007, 6_011, 6_029, 6_037, 6_043, 6_047, 6_053, 6_067, 6_073, 6_079, 6_089, 6_091, 6_101, 6_113, 6_121, 6_131, 6_133, 6_143, 6_151, 6_163, 6_173, 6_197, 6_199, 6_203, 6_211, 6_217, 6_221, 6_229, 6_247, 6_257, 6_263, 6_269, 6_271, 6_277, 6_287, 6_299, 6_301, 6_311, 6_317, 6_323, 6_329, 6_337, 6_343, 6_353, 6_359, 6_361, 6_367, 6_373, 6_379, 6_389, 6_397, 6_421, 6_427, 6_449, 6_451, 6_469, 6_473, 6_481, 6_491, 6_521, 6_529, 6_547, 6_551, 6_553, 6_563, 6_569, 6_571, 6_577, 6_581, 6_599, 6_607, 6_619, 6_637, 6_653, 6_659, 6_661, 6_673, 6_679, 6_689, 6_691, 6_701, 6_703, 6_709, 6_719, 6_733, 6_737, 6_761, 6_763, 6_779, 6_781, 6_791, 6_793, 6_803, 6_823, 6_827, 6_829, 6_833, 6_841, 6_857, 6_863, 6_869, 6_871, 6_883, 6_899, 6_907, 6_911, 6_917, 6_947, 6_949, 6_959, 6_961, 6_967, 6_971, 6_977, 6_983, 6_991, 6_997, 7_001, 7_013, 7_019, 7_027, 7_039, 7_043, 7_057, 7_069, 7_079, 7_103, 7_109, 7_121, 7_127, 7_129, 7_151, 7_159, 7_177, 7_187, 7_193, 7_207, 7_211, 7_213, 7_219, 7_229, 7_237, 7_243, 7_247, 7_253, 7_283, 7_297, 7_307, 7_309, 7_321, 7_331, 7_333, 7_349, 7_351, 7_369, 7_393, 7_411, 7_417, 7_433, 7_451, 7_457, 7_459, 7_477, 7_481, 7_487, 7_489, 7_499, 7_507, 7_517, 7_523, 7_529, 7_537, 7_541, 7_547, 7_549, 7_559, 7_561, 7_573, 7_577, 7_583, 7_589, 7_591, 7_603, 7_607, 7_621, 7_639, 7_643, 7_649, 7_669, 7_673, 7_681, 7_687, 7_691, 7_699, 7_703, 7_717, 7_723, 7_727, 7_741, 7_753, 7_757, 7_759, 7_789, 7_793, 7_817, 7_823, 7_829, 7_841, 7_853, 7_867, 7_873, 7_877, 7_879, 7_883, 7_901, 7_907, 7_919];

		foreach ($numbers as $number) {
			yield $number;
		}
	}
}
