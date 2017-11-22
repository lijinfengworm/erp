<?php
class AppFilterService
{

    public static $_hrefFilterReg = array(
        '/^http:\/\/www\.shihuo\.cn\/youhui(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/youhui(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/youhui\/(\d+).html(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/youhui\/(\d+).html(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/haitao(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/haitao\/index(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/haitao\/youhui(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/haitao(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/haitao\/youhui\/(\d+).html(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/haitao\/youhui\/(\d+).html(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/haitao\/daigou(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/daigou(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/haitao\/buy\/([\d-]+).html(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/daigou\/([\d-]+).html(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/tuangou(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/tuangou(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/tuangou\/(\d+)(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/tuangou\/(\d+)(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/shoe(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/shoe(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/detail\/(\d+).html(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/shoe\/detail\/(\d+).html(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/shaiwu(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/shaiwu(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/shaiwu\/detail\/(\d+).html(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/shaiwu\/detail\/(\d+)(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/coupons\/quan(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/coupon(#*|#.*)$/i',
        '/^http:\/\/www\.shihuo\.cn\/duihuan\/(\d+).html(#*|#.*)$/i',
        '/^http:\/\/m\.shihuo\.cn\/coupon\/(\d+).html(#*|#.*)$/i'
    );
}